<?php
/**
 * @version     1.0.0
 * @package     com_quickcontent
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Quickcontent records.
 */
class QuickcontentModelGenerator extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'content', 'a.content',
                'title', 'a.title',

            );
        }
		$this->app = JFactory::getApplication() ;
		$this->categoryTable 	= JTable::getInstance( 'category' );
		$this->articleTable 	= JTable::getInstance( 'content' );
		$this->menuTable 		= JTable::getInstance( 'menu' );
		$this->params 			= JComponentHelper::getParams('com_quickcontent');
		
		//$this->es = AK::getEasySet();
		
        parent::__construct($config);
    }

	public function saveContent() {
		
		$content = $this->getContent( JRequest::getVar('id') ) ;
		$this->content = $content ;
		
		$this->createMenutype() ;
		
		// fix Editor and MS Word HTML different bug
		$content->content = $this->fixMceHtml($content->content) ;
		$dom = simplexml_load_string( '<root>'.$content->content.'</root>');
		
		$this->getCategories( $dom );
	}
	
	public function getCategories( $cats = null , $parent_id = null , $level = 1 ){
		
		if( !$cats ) return ;
		
		if( !$parent_id ) $parent_id = array( 'cat' => 1 , 'menu' => 1 );
		
		
		
		if( $cats->ul->li ) {
			foreach($cats->ul->li as $li ) {
				
				if( $li->strong ) {
					// create Article and menu
					echo 'article:'.$li->strong.'<br />' ;
					$this->createArticle( $li , $parent_id ) ;
					
				}else{
					// create Category and menu
					//echo 'category:'.$li.$parent_id['cat'].'<br />' ;
					$id = $this->createCategory( $li , $parent_id ) ;
					
					$this->getCategories( $li , $id ) ;
				}

			}
		}
		
	}
	
	public function createCategory($cat,$pid,$level=1) {
		
		$title = (string)$cat ;
		$title = trim( $title ) ;
		
		// set Category
		$t = $this->categoryTable ;
		$t->reset();
		
		$t->id 			= null ;
		$t->title 		= $title ;
		$t->parent_id 	= $pid['cat'] ;
		$t->published 	= 1 ;
		$t->extension 	= 'com_content' ;
		$t->access 		= 1 ;
		$t->language 	= '*' ;
		
		$t->setLocation( $pid['cat'] , 'last-child' ) ;
		
		$this->tranAlias( &$t );
		$t->check();
		$this->app->triggerEvent( 'onContentBeforeSave' , array( 'com_categories.category', &$t, true ) ) ;
		
		// is same alias exists?
		$t2 = JTable::getInstance('Category','JTable');
		$titleTmp = $t->title ;
		$aliasTmp = $t->alias ;
		$i = 2 ;
		
		while( $t2->load(array('alias'=>$t->alias,'parent_id'=>$t->parent_id,'extension'=>$t->extension)) 
		&& ($t2->id != $t->id || $t->id==0) ) :
			
			if( $this->params->get('titlesufix_when_alias_exists' , false ) )
				$t->title = $titleTmp." ({$i})" ;
			
			$t->alias = $aliasTmp."-{$i}" ;
			
			$i++ ;
		endwhile;
		
		// OK, store!
		$t->store();
		$this->app->triggerEvent( 'onContentAfterSave' , array( 'com_content.article', &$t, true ) ) ;
		
		$pid['cat'] = $t->id;
		
		// set Menu
		$layout = $this->content->category_menutype ;
		switch( $layout ){
			case 'list' :
				$link  	= "index.php?option=com_content&view=category&id={$pid['cat']}" ;
				$params = $this->content->list ;
				$type 	= 'component' ;
				$conponent_id = 22 ;
			break;
			
			case 'blog' :
				$link  	= "index.php?option=com_content&view=category&layout=blog&id={$pid['cat']}" ;
				$params = $this->content->blog ;
				$type 	= 'component' ;
				$conponent_id = 22 ;
			break;
			
			case 0 :
				$type 			= 'url' ;
				$link 			= 'javascript:void(0);' ;
				$conponent_id 	= 0 ;
			break;
		}
		
		$m = $this->menuTable ;
		$m->reset();
		
		$m->title 		= $t->title ;
		$m->alias		= $t->alias ;
		$m->parent_id 	= $pid['menu'];
		$m->link 		= $link ;
		$m->type 		= $type ;
		$m->published 	= 1 ;
		$m->access 		= 1 ;
		$m->language 	= '*' ;
		$m->component_id= $conponent_id ;
		$m->params 		= $params ;
		$m->menutype 	= $this->content->menutype ;
		
		$m->setLocation( $pid['menu'] , 'last-child' ) ;
		
		$m->check();
		$m->store();
		
		$pid['menu'] = $m->id;
		$m->reset();
		$m->id = null ;
		
		return $pid ;
	}
	
	public function createMenutype() {
		
		$menutype = $this->content->menutype ;
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("*")
			->from("#__menu_types")
			->where("menutype='{$menutype}'")
			;
		
		$db->setQuery($q);
		$result = $db->loadObject();
		
		if(!$result) {
			
			$my = JTable::getInstance( 'menuType' );
			$my->menutype 		= $menutype ;
			$my->title 			= $menutype ;
			$my->description 	= $menutype ;
			
			$my->check();
			$my->store();
		}
		
		if( $this->content->delete_existing ) {
			$this->deleteMenus( $menutype ) ;
		}
		
	}
	
	public function createArticle($title,$pid){
		
		//if( $pid['cat'] == 1 ) $pid['cat'] = 9 ;
		
		$t = $this->articleTable ;
		$t->reset();
		$t->id = null ;
		
		$title = (string)$title->strong ;
		$title = trim( $title ) ;
		
		$lorem = $this->params->get( 'lorem' );
		
		if( !$lorem ):
		
			$dIntrotext 	= 
<<<INTRO
			<p><img src="http://placehold.it/200x300" alt="placeholder" width="200" height="300" style="margin:0 5px 5px 0;" align="left">彼得，說你在坐車裏常常伸出你的小手在車欄上跟著音樂按拍；你稍大些會得淘氣的時候，何嘗沒有羨慕的時候，但想起我做父親的往迹，與你一撮的遺灰，裝一個走江湖的桀卜閃人，因此我有時想，同在一個神奇的宇宙裡自得。</p>
			<p>上山或是下山，那天在柏林的會館裏，只要把話匣開上，這問的後身便是無限的隱痛；我不能怨，但你應得帶書，你應得躲避她像你躲避青草裡一條美麗的花蛇！</p>
			<p>你便蓋沒了你的小耳，這問的後身便是無限的隱痛；我不能怨，誰沒有悵惘？你是不認識你父親的，也不能給我們利益，我心頭便湧起了不少的感想；我的話你是永遠聽不著了，我們的鏈永遠是制定我們行動的上司！大大記得最清楚，可以懂得我話裏意味的深淺，約莫八九歲光景，你應得躲避她像你躲避青草裡一條美麗的花蛇！</p>
			<p>假如你長大的話，極端的自私，我心頭便湧起了不少的感想；我的話你是永遠聽不著了，并且假如我這番不到歐洲，流入嫵媚的阿諾河去……並且你不但不須應伴，那才是你實際領受，他們的獨子，那邊每株樹上都是滿掛著詩情最秀逸的果實，小鵝，一般紫的紫籐，體魄與性靈，同在一個神奇的宇宙裡自得。</p>
INTRO;
			$dFulltext 	= 
<<<FULL
			<p>彼得，說你在坐車裏常常伸出你的小手在車欄上跟著音樂按拍；你稍大些會得淘氣的時候，何嘗沒有羨慕的時候，但想起我做父親的往迹，與你一撮的遺灰，裝一個走江湖的桀卜閃人，因此我有時想，同在一個神奇的宇宙裡自得。</p>
			<p>上山或是下山，那天在柏林的會館裏，只要把話匣開上，這問的後身便是無限的隱痛；我不能怨，但你應得帶書，你應得躲避她像你躲避青草裡一條美麗的花蛇！</p>
			<p>你便蓋沒了你的小耳，這問的後身便是無限的隱痛；我不能怨，誰沒有悵惘？你是不認識你父親的，也不能給我們利益，我心頭便湧起了不少的感想；我的話你是永遠聽不著了，我們的鏈永遠是制定我們行動的上司！大大記得最清楚，可以懂得我話裏意味的深淺，約莫八九歲光景，你應得躲避她像你躲避青草裡一條美麗的花蛇！</p>
			<p>你便蓋沒了你的小耳，這問的後身便是無限的隱痛；我不能怨，誰沒有悵惘？你是不認識你父親的，也不能給我們利益，我心頭便湧起了不少的感想；我的話你是永遠聽不著了，我們的鏈永遠是制定我們行動的上司！大大記得最清楚，可以懂得我話裏意味的深淺，約莫八九歲光景，你應得躲避她像你躲避青草裡一條美麗的花蛇！</p>
			<p>假如你長大的話，極端的自私，我心頭便湧起了不少的感想；我的話你是永遠聽不著了，并且假如我這番不到歐洲，流入嫵媚的阿諾河去……並且你不但不須應伴，那才是你實際領受，他們的獨子，那邊每株樹上都是滿掛著詩情最秀逸的果實，小鵝，一般紫的紫籐，體魄與性靈，同在一個神奇的宇宙裡自得。</p>
FULL;
		else:
			
			$lorem = explode( '<hr id="system-readmore" />' , $lorem ) ;
			
			$dIntrotext = $lorem[0] ;
			
			$dFulltext 	= $lorem[1] ;
			
		endif;
		
		$t->title 		= $title ;
		$t->introtext 	= $dIntrotext ;
		$t->fulltext 	= $dFulltext ;
		$t->state 		= 1 ;
		$t->catid 		= $pid['cat'] ;
		$t->access 		= 1 ;
		$t->language 	= '*' ;
		//$t->  ;
		//$t->  ;
		
		$this->tranAlias( &$t );
		$t->check();
		$this->app->triggerEvent( 'onContentBeforeSave' , array( 'com_content.article', &$t, true ) ) ;
		
		// is same alias exists?
		$t2 = JTable::getInstance('Content','JTable');
		$titleTmp = $t->title ;
		$aliasTmp = $t->alias ;
		$i = 2 ;
		
		while ($t2->load(array('alias'=>$t->alias,'catid'=>$t->catid)) 
		&& ($t2->id != $t->id || $t->id==0)) :
			
			if( $this->params->get('titlesufix_when_alias_exists' , false ) )
				$t->title = $titleTmp." ({$i})" ;
			
			$t->alias = $aliasTmp."-{$i}" ;
			
			$i++ ;
		endwhile;
		
		
		$t->store();
		$this->app->triggerEvent( 'onContentAfterSave' , array( 'com_content.article', &$t, true ) ) ;
		
		// set Menu
		$link  = "index.php?option=com_content&view=article&id={$t->id}" ;
		
		$m = $this->menuTable ;
		$m->reset();
		
		$m->title 		= $t->title ;
		$m->alias 		= $t->alias ;
		$m->parent_id 	= $pid['menu'];
		$m->link 		= $link ;
		$m->type 		= 'component' ;
		$m->published 	= 1 ;
		$m->access 		= 1 ;
		$m->language 	= '*' ;
		$m->component_id= 22 ;
		$m->params 		= $this->content->article  ;
		$m->menutype 	= $this->content->menutype ;
		
		$m->setLocation( $pid['menu'] , 'last-child' ) ;
		
		$m->check();
		$m->store();
		
		$m->reset();
		$m->id = null ;
		
		return $pid ;
	}
	
	public function getContent( $id ) {
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("*")
			->from("#__quickcontent_lists")
			->where("id='{$id}'")
			;
		
		$db->setQuery($q);		
		$result = $db->loadObject();
		
		return $result ;
	}
	
	public function deleteAll() {
		
		$this->deleteArticles();
		
		$this->deleteCatrgories();
		
		$this->deleteMenus();
		
		$this->deleteMenuTypes();
	}
	
	public function deleteMenuTypes(){
		// Delete MenuTypes
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("id")
			->from("#__menu_types")
			;
		
		$db->setQuery($q);
		$cids = $db->loadColumn();
		
		$content = JTable::getInstance( 'menutype' );
		
		foreach( $cids as $cid ) {
			$content->load( $cid );
			if( !in_array( $content->menutype , $this->default_menu ) ) {
				$content->delete() ;
			}
		}
	}
	
	public function deleteMenus( $menutype = null ){
	
		if( $menutype ){
			$where = " menutype='{$menutype}' " ;
		} else{
			$where = " menutype != 'menu' AND menutype != 'main' AND level != 0 " ;
		}
	
		// Delete Menus
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("id")
			->from("#__menu")
			;
		
		$db->setQuery($q);
		$cids = $db->loadColumn();
		
		$content = JTable::getInstance( 'menu' );
		$default_menu = array();
		
		foreach( $cids as $cid ) {
			$content->load( $cid );
			
			if( $content->home != 0 ) {
				$default_menu[] = $content->menutype ;
			}else{
				$content->delete() ;
			}
		}
		
		$this->default_menu = $default_menu ;
	}
	
	public function deleteCatrgories(){
		// Delete Categories
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("id")
			->from("#__categories")
			->where("level != 0")
			->where("extension='com_content'")
			;
		
		$db->setQuery($q);
		$cids = $db->loadColumn();
		
		$content = JTable::getInstance( 'category' );
		
		foreach( $cids as $cid ) {
			$content->load( $cid );
			if( $content->title != 'uncategorized' )
				$content->delete() ;
		}
	}
	
	public function deleteArticles(){
		// Delete Categories
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("id")
			->from("#__content")
			;
		
		$db->setQuery($q);
		$cids = $db->loadColumn();
		
		$content = JTable::getInstance( 'content' );
		
		foreach( $cids as $cid ) {
			$content->load( $cid );
			$content->delete() ;
		}
	}
	/*
	* turn 
	* 	<li>Something<li>
	* 	<ul>
	* 		<li>Something</li>
	* 	</ul>	
	* to
	* 	<li>Something
	*		<ul>
	*			<li>Something</li>
	*		</ul>
	*	</li>
	*	
	*	Otherwise the DOM parser will misunstand the categories level		
	*/
	public function fixMceHtml($c) {
		
		
		$c = nl2br($c);

		$c = explode( "<br />" , $c );
		$o = '';
		foreach( $c as $k => $v ) {
			$o .= trim($v);
		}
		
		$o = str_replace( '</li><ul>' , '<ul>' , $o );
		$o = str_replace( '</ul></ul>' , '</ul></li></ul>' , $o );
		$o = str_replace( '</ul><li>' , '</ul></li><li>' , $o );
		
		return $o ;
	}
	
	public function tranAlias( &$article) {
	    $alias = $article->alias ;
	    $title = $article->title ;
	    
	    $titleTmp = explode( '::' , $title );
	    if( $titleTmp[1] ) :
			$title = $titleTmp[0];
			$alias = $titleTmp[1];
		endif;
		
		$alias = JFilterOutput::stringURLSafe($alias);
		
		if( trim($alias) == '' ) :
			$alias = AKHelper::_( 'lang.translate' , $title, 'zh-tw', 'en');
			$alias = trim( $alias );
			$alias = JFilterOutput::stringURLSafe($alias);
			
			$replace = array(	'aquot' => '' ,
								'a39'	=> '' ,
								'--'	=> '-'
								);
			$alias = strtr( $alias , $replace );
			$alias = trim( $alias , '-' );
		endif;
		
		$article->title = trim($title) ;
		$article->alias = trim($alias) ;
	}
	
}
