<?php
use SaltedHerring\Utilities as Utilities;
use SaltedHerring\Grid as Grid;

class Page extends SiteTree {

    private static $db = array(
    );

    private static $has_one = array(
    );

    private static $has_many = array(
        'Plugables'		=>	'DualColModel'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        if ($this->ClassName == 'Page' && !empty($this->ID)) {
            $fields->addFieldToTab(
                'Root.ModulaContent',
                Grid::make('Plugables', '内容条', $this->Plugables())
            );
            $fields->fieldByName('Root.ModulaContent')->setTitle('内容条');
        }

        return $fields;
    }

}

class Page_Controller extends ContentController {
    protected static $extensions = array(
        'SiteJSControllerExtension'
    );
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = array (
    );

    public function init() {
        parent::init();
        // Poli::initiate(12);
        Requirements::block(THIRDPARTY_DIR . '/jquery/jquery.js');

        // Session::start();

        if ($this->request->getVar('url') != '/cart/payment' && !$this->request->isAjax() && $this->request->getVar('url') != '/cart/PaymentHandler') {
            Session::clear('page_refreshable');
        }

        // Session::save();

        // SaltedHerring\Debugger::inspect(Session::get_all());
        $this->initJS();
        // Note: you should use SS template require tags inside your templates
        // instead of putting Requirements calls here.  However these are
        // included so that our older themes still work
        /*
        Requirements::themedCSS('reset');
        Requirements::themedCSS('layout');
        Requirements::themedCSS('typography');
        Requirements::themedCSS('form');
        */

        if ($member = Member::currentUser()) {
            if ($member->inGroup('administrators')) {
                SSViewer::set_theme('nzyogo');
            }
        }
    }

    public function setMessage($type, $message)
    {
        Session::set('Message', array(
            'MessageType' => $type,
            'Message' => $message
        ));
    }

    public function getMessage()
    {
        if($message = Session::get('Message')){
            Session::clear('Message');
            $array = new ArrayData($message);
            return $array->renderWith('Message');
        }
    }

    protected function getSessionID() {
        return session_id();
    }

    protected function getHTTPProtocol() {
        $protocol = 'http';
        if (isset($_SERVER['SCRIPT_URI']) && substr($_SERVER['SCRIPT_URI'], 0, 5) == 'https') {
            $protocol = 'https';
        } elseif (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
            $protocol = 'https';
        }
        return $protocol;
    }

    protected function getCurrentPageURL() {
        return $this->getHTTPProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    public function MetaTags($includeTitle = true) {
        $tags = parent::MetaTags();
        /*
if($includeTitle === true || $includeTitle == 'true') {
            $tags = preg_replace('/(\<title\>.*\<\/title\>)/', "<title>" . $this->getTheTitle() . "</title>\n", $tags);
        }
*/

        $charset = ContentNegotiator::get_encoding();
        $tags .= "<meta http-equiv=\"Content-type\" content=\"text/html; charset=$charset\" />\n";
        if($this->MetaKeywords) {
            $tags .= "<meta name=\"keywords\" content=\"" . Convert::raw2att($this->MetaKeywords) . "\" />\n";
        }
        if($this->MetaDescription) {
            $tags .= "<meta name=\"description\" content=\"" . Convert::raw2att($this->MetaDescription) . "\" />\n";
        }
        if($this->ExtraMeta) {
            $tags .= $this->ExtraMeta . "\n";
        }

        if($this->URLSegment == 'home' && SiteConfig::current_site_config()->GoogleSiteVerificationCode) {
            $tags .= '<meta name="google-site-verification" content="'
                    . SiteConfig::current_site_config()->GoogleSiteVerificationCode . '" />\n';
        }

        // prevent bots from spidering the site whilest in dev.
        if(!Director::isLive()) {
            $tags .= "<meta name=\"robots\" content=\"noindex, nofollow, noarchive\" />\n";
        }

        $this->extend('MetaTags', $tags);

        return $tags;
    }

    public function getTheTitle() {
        return Convert::raw2xml(($this->MetaTitle) ? $this->MetaTitle : $this->Title);
    }

    public function getBodyClass() {
        return Utilities::sanitiseClassName($this->singular_name(),'-');
    }

    public function getFreightServices() {
        return FreightOption::get()->filter('Title:not', '自(己到店里来)取');
    }

    public function getLatestUpdates() {
        return Versioned::get_by_stage('SoftAdsPage', 'Live')->limit(3);
    }

    public function getCrumbs() {
        $crumbs = array();
        $language = $this->getLanguage();
        $crumbs[] = array('Link' => '/', 'Title' => ($language == 'Chinese' ? '首页' : 'Home'));
        $ancestors = array_reverse($this->getAncestors()->toArray());
        foreach($ancestors as $ancestor) {
            $crumbs[] = array('Link' => $ancestor->Link(), 'Title' => $ancestor->Title);
        }

        $crumbs[] = array('Title' => $this->Title);

        return new ArrayList($crumbs);
    }

    public function getLanguage()
    {
        if ($member = Member::currentUser()) {
            $language = Member::currentUser()->Language;
            $language = !empty($language) ? $language : Translatable::get_current_locale();
        } else {
            $language = Translatable::get_current_locale();
        }

        return $language == 'zh_Hans' ? 'Chinese' : 'English';

    }
}
