<?php

class Content_Controller extends Pelican_Controller_Front
{

    public function indexAction ()
    {
        
        $content = Pelican_Cache::fetch("Frontend/Content", array(
            $_GET["cid"] , 
            $_SESSION[APP]['SITE_ID'] , 
            $_SESSION[APP]['LANGUE_ID'] , 
            Pelican::getPreviewVersion() , 
            "" , 
            date("d.m.y")
        ));
        
        $this->contentModel();
        $this->assign("category", $content['CONTENT_CATEGORY_LABEL']);
        $this->assign("title", $content['CONTENT_TITLE']);
        $this->assign("subtitle", $content['CONTENT_SUBTITLE']);
        $this->assign("shorttext", $content['CONTENT_SHORTTEXT']);
        $this->assign("text", $content['CONTENT_TEXT']);
        if (count($content['TAGS'])) {
            $this->assign("tags", $content['TAGS']);
        }
        
        $author = str_replace(array(
            '#admin#' , 
            '##' , 
            '#'
        ), array(
            '' , 
            ',' , 
            ''
        ), $content['CONTENT_CREATION_USER']);
        $this->assign("author", $author);
        $this->assign("aContenu", $content);
        $this->assign("id", $content['CONTENT_ID']);
        $this->fetch();
    }

    /**
     * __DESC__
     *
     * @access protected
     * @return __TYPE__
     */
    protected function contentModel ()
    {
        $this->assign("template", $this->getTemplate());
        $this->setTemplate(Pelican::$config['APPLICATION_VIEWS'] . '/Content/Model/index.tpl');
    }
}
