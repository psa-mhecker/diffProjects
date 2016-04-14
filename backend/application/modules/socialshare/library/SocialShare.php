<?php
class Plugin_SocialShare
{
    public static $bookmark = array(
  array('aim','aim','AIM'),
  array('allvoices','allvoices','Allvoices'),
  array('amazon_wish_list','amazon','Amazon Wish List'),
  array('arto','arto','Arto'),
  array('ask.com_mystuff','ask','Ask.com MyStuff'),
  array('backflip','backflip','Backflip'),
  array('bebo','bebo','Bebo'),
  array('bibsonomy','bibsonomy','BibSonomy'),
  array('bitty_browser','bitty','Bitty Browser'),
  array('blinklist','blinklist','Blinklist'),
  array('blogger_post','blogger','Blogger Post'),
  array('blogmarks','blogmarks','BlogMarks'),
  array('bookmarks.fr','bookmarks_fr','bookmarks.fr'),
  array('box','box','Box.net'),
  array('buddymarks','buddymarks','BuddyMarks'),
  array('business_exchange','business_exchange','Business Exchange'),
  array('care2_news','care2','Care2 News'),
  array('citeulike','citeulike','CiteULike'),
  array('connotea','connotea','Connotea'),
  array('current','current','Current'),
  array('buffer','default','Buffer'),
  array('kindle','default','Kindle It'),
  array('delicious','delicious','Delicious'),
  array('digg','digg','Digg'),
  array('diglog','diglog','Diglog'),
  array('diigo','diigo','Diigo'),
  array('dzone','dzone','DZone'),
  array('evernote','evernote','Evernote'),
  array('facebook','facebook','Facebook'),
  array('fark','fark','Fark'),
  array('faves','faves','Faves'),
  array('folkd','folkd','Folkd'),
  array('friendfeed','friendfeed','FriendFeed'),
  array('funp','funp','FunP'),
  array('gabbr','gabbr','Gabbr'),
  array('gmail','gmail','Gmail'),
  array('google_bookmarks','google','Google bookmarks'),
  array('google_plus','google_plus','Google+'),
  array('hellotxt','hellotxt','HelloTxt'),
  array('hemidemi','hemidemi','Hemidemi'),
  array('hugg','hugg','Hugg'),
  array('hyves','hyves','Hyves'),
  array('identi.ca','identica','Identi.ca'),
  array('imera_brazil','imera','Imera Brazil'),
  array('instapaper','instapaper','Instapaper'),
  array('jamespot','jamespot','Jamespot'),
  array('jumptags','jumptags','Jumptags'),
  array('khabbr','khabbr','Khabbr'),
  array('kledy','kledy','Kledy'),
  array('linkagogo','linkagogo','LinkaGoGo'),
  array('linkatopia','linkatopia','Linkatopia'),
  array('linkedin','linkedin','LinkedIn'),
  array('hotmail','live','Hotmail'),
  array('livejournal','livejournal','LiveJournal'),
  array('maple','maple','Maple'),
  array('meneame','meneame','Meneame'),
  array('messenger','messenger','Messenger'),
  array('mister-wong','mister-wong','Mister-Wong'),
  array('mozillaca','mozillaca','Mozillaca'),
  array('multiply','multiply','Multiply'),
  array('mylinkvault','mylinkvault','MyLinkVault'),
  array('myspace','myspace','MySpace'),
  array('netlog','netlog','Netlog'),
  array('netvibes_share','netvibes','Netvibes Share'),
  array('netvouz','netvouz','Netvouz'),
  array('newstrust','newstrust','NewsTrust'),
  array('newsvine','newsvine','NewsVine'),
  array('nowpublic','nowpublic','NowPublic'),
  array('oneview','oneview','Oneview'),
  array('orkut','orkut','Orkut'),
  array('phonefavs','phonefavs','PhoneFavs'),
  array('ping','ping','Ping'),
  array('plaxo_pulse','plaxo','Plaxo Pulse'),
  array('plurk','plurk','Plurk'),
  array('posterous','posterous','Posterous'),
  array('printfriendly','printfriendly','PrintFriendly'),
  array('protopage_bookmarks','protopage','Protopage bookmarks'),
  array('pusha','pusha','Pusha'),
  array('read_it_later','read_it_later','Read It Later'),
  array('reader','reader','Google Reader'),
  array('reddit','reddit','Reddit'),
  array('rediff','rediff','Rediff MyPage'),
  array('segnalo','segnalo','Segnalo'),
  array('simpy','simpy','Simpy'),
  array('sitejot','sitejot','SiteJot'),
  array('slashdot','slashdot','Slashdot'),
  array('smaknews','smaknews','SmakNews'),
  array('sphere','sphere','Sphere'),
  array('sphinn','sphinn','Sphinn'),
  array('spurl','spurl','Spurl'),
  array('squidoo','squidoo','Squidoo'),
  array('startaid','startaid','StartAid'),
  array('strands','strands','Strands'),
  array('stumbleupon','stumbleupon','StumbleUpon'),
  array('symbaloo_feeds','symbaloo','Symbaloo Feeds'),
  array('tagza','tagza','Tagza'),
  array('tailrank','tailrank','Tailrank'),
  array('technorati_favorites','technorati','Technorati Favorites'),
  array('technotizie','technotizie','Technotizie'),
  array('tipd','tipd','Tipd'),
  array('tuenti','tuenti','Tuenti'),
  array('tumblr','tumblr','Tumblr'),
  array('twiddla','twiddla','Twiddla'),
  array('twitter','twitter','Twitter'),
  array('typepad_post','typepad','TypePad Post'),
  array('unalog','unalog','Unalog'),
  array('viadeo','viadeo','Viadeo'),
  array('vodpod','vodpod','VodPod'),
  array('webnews','webnews','Webnews'),
  array('wink','wink','Wink'),
  array('wists','wists','Wists'),
  array('wordpress','wordpress','WordPress'),
  array('xerpi','xerpi','Xerpi'),
  array('xing','xing','XING'),
  array('yahoo_bookmarks','yahoo','Yahoo bookmarks'),
  array('yahoo_mail','yahoo','Yahoo Mail'),
  array('yample','yample','Yample'),
  array('yigg','yigg','YiGG'),
  array('yim','yim','Yahoo Messenger'),
  array('yoolink','yoolink','Yoolink'),
  array('youmob','youmob','YouMob'),
    );

    public static function getBookmarks()
    {
        //http://ekstreme.com/socializer/?title=title&url=permalink
        return array(
        'Delicious' => 'http://del.icio.us/post?title=%title%&amp;url=%permalink%'
        ,'Digg' => 'http://digg.com/submit?phase=2&amp;title=%title%&amp;url=%permalink%'
        ,'Furl' => 'http://www.furl.net/storeIt.jsp?t=%title%&amp;u=%permalink%'
        ,'Reddit' => 'http://reddit.com/submit?title=%title%&amp;url=%permalink%'
        ,'Ask' => 'http://myjeeves.ask.com/mysearch/BookmarkIt?v=1.2&amp;t=webpages&amp;title=%title%&amp;url=%permalink%'
        ,'Bebo' => 'http://www.bebo.com/PleaseSignIn.jsp?Page=c/share&Url=%permalink%&Title=%title%&popup=0'
        ,'BlinkList' => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Title=%title%&amp;Description=&amp;Url=%permalink%'
        ,'blogmarks' => 'http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;title=%title%&amp;url=%permalink%'
        ,'Blogg-Buzz' => 'http://www.blogg-buzz.com/submit.php?url=%permalink%'
        ,'Google' => 'http://www.google.com/bookmarks/mark?op=add&amp;title=%title%&amp;bkmk=%permalink%'
        ,'Google Reader' => 'http://www.google.com/reader/link?url=%permalink%&title=%title%&snippet='
        ,'Ma.gnolia' => 'http://ma.gnolia.com/beta/bookmarklet/add?title=%title%&amp;description=%title%&amp;url=%permalink%'
        ,'Myspace' => 'http://www.myspace.com/Modules/PostTo/Pages/?u=%permalink%&t=%title%&l=3&c='
        ,'Netscape' => 'http://www.netscape.com/submit/?T=%title%&amp;U=%permalink%'
        ,'Evernote' => 'http://www.evernote.com/clip.action?url=%permalink%&title=%title%'
        ,'ppnow' => 'http://www.ppnow.com/submit.php?url=%permalink%'///
        ,'Rojo' => 'http://www.rojo.com/submit/?title=%title%&amp;url=%permalink%'
        ,'Shadows' => 'http://www.shadows.com/features/tcr.htm?title=%title%&amp;url=%permalink%'
        ,'Simpy' => 'http://www.simpy.com/simpy/LinkAdd.do?title=%title%&amp;href=%permalink%'
        ,'Socializer' => 'http://ekstreme.com/socializer/?title=%title%&amp;url=%permalink%'
        ,'Spurl' => 'http://www.spurl.net/spurl.php?title=%title%&amp;url=%permalink%'
        ,'StumbleUpon' => 'http://www.stumbleupon.com/submit?title=%title%&amp;url=%permalink%'
        ,'Tailrank' => 'http://tailrank.com/share/?link_href=%permalink%&amp;title=%title%'
        ,'Technorati' => 'http://www.technorati.com/faves?add=%permalink%'
        ,'Live Bookmarks' => 'https://favorites.live.com/quickadd.aspx?marklet=1&amp;mkt=en-us&amp;title=%title%&amp;url=%permalink%&amp;top=1'
        ,'Wists' => 'http://wists.com/r.php?c=&amp;title=%title%&amp;r=%permalink%'
        ,'Yahoo! Myweb' => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?title=%title%&amp;popup=true&amp;u=%permalink%'
        ,'BobrDobr' => 'http://bobrdobr.ru/addext.html?url=%permalink%&amp;title=%title%'
        ,'Memori' => 'http://memori.ru/link/?sm=1&amp;u_data[url]=%permalink%&amp;u_data[name]=%title%'
        ,'Faves' => 'http://faves.com/Authoring.aspx?u=%title%&amp;t=%title%'
        ,'Favorites' => "javascript: (function (url,title) {var e; try {if (document.all&&!window.opera) window.external.AddFavorite(url, title); else if (window.opera) {var el=document.createElement(&quot;a&quot;); el.rel=&quot;sidebar&quot;;el.href=url;el.title=title;el.click();} else window.sidebar.addPanel(title, url,&quot;&quot;);} catch (e) {}})(&quot;%permalink%&quot;,&quot;%title%&quot;);"
        ,'Facebook' => 'http://www.facebook.com/sharer.php?u=%permalink%&amp;t=%title%'
        ,'Newsvine' => 'http://www.newsvine.com/_tools/seed&amp;save?u=%permalink%&amp;h=%title%'
        ,'Yahoo! Bookmarks' => 'http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&amp;u=%permalink%&t=%title%'
        ,'Twitter' => 'http://twitter.com/home?status=%title%:%permalink%'
        ,'myAOL' => 'http://favorites.my.aol.com/ffclient/AddBookmark?url=%permalink%&amp;title=%title%&amp;favelet=true'
        ,'Slashdot' => 'http://slashdot.org/bookmark.pl?url=%permalink%'
        ,'Fark' => 'http://cgi.fark.com/cgi/fark/submit.pl?new_url=%permalink%&amp;new_comment=%title%'
        ,'RawSugar' => 'http://www.rawsugar.com/tagger/?turl=%permalink%&amp;tttl=%title%&amp;editorInitialized=1'
        ,'LinkaGoGo' => 'http://www.linkagogo.com/go/AddNoPopup?url=%permalink%&amp;title=%title%'
        ,'Mister Wong' => 'http://www.mister-wong.de/index.php?action=addurl&amp;bm_url=%permalink%&amp;bm_description=%title%'
        ,'Wink' => 'http://www.wink.com/_/tag?url=%permalink%&amp;doctitle=%title%'
        ,'BackFlip' => 'http://www.backflip.com/add_page_pop.ihtml?url=%permalink%&amp;title=%title%'
        ,'Diigo' => 'http://www.diigo.com/post?url=%permalink%&amp;title=%title%'
        ,'Segnalo' => 'http://segnalo.com/post.html.php?url=%permalink%&amp;title=%title%'
        ,'Netvouz' => 'http://netvouz.com/action/submitBookmark?url=%permalink%&amp;title=%title%&amp;popup=no'
        ,'DropJack' => 'http://www.dropjack.com/submit.php?url=%permalink%'
        ,'Feed Me Links' => 'http://feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;url=%permalink%&amp;name=%title%'
        ,'funP' => 'http://funp.com/push/submit/add.php?url=%permalink%&amp;s=%title%'
        ,'HEMiDEMi' => 'http://www.hemidemi.com/user_bookmark/new?title=%title%&amp;url=%permalink%'
        ,'PhoneFavs' => 'http://phonefavs.com/login/?action=add&address=%permalink%&title=%title%'
        ,'Live Journal' => 'http://www.livejournal.com/update.bml?subject=%title%&event=%permalink%'
        ,'PrintFriendly' => 'http://www.printfriendly.com/print?url=%permalink%&partner=a2a'
        ,'MSDN Social' => 'http://social.msdn.microsoft.com/fr-FR/action/Create/s/E/?url=%permalink%&bm=true&ttl=%title%&d='
        ,'CiteULike' => 'http://www.citeulike.org/posturl2?username=&url=%permalink%&title=%title%&tags=&rewrite=yes&ourl=%permalink%'
        , 'AddToAny' => 'http://www.addtoany.com/share_save?linkname=%title%&linkurl=%permalink%',
        );
    }

    public static function getLinks($aValues, $aSelected)
    {
        $return = array();
        $values = array(urlencode($aValues[0]),urlencode($aValues[1]));

        $aBookmarks = self::getBookmarks();

        foreach ($aBookmarks as $type => $link) {
            $type = str_replace(' ', '_', strtolower($type));
            if (is_array($aSelected)) {
                if (in_array($type, $aSelected)) {
                    $return[$type] = str_replace(array('%title%', '%permalink%'), $values, $link);
                }
            }
        }

        return $return;
    }
}
//,'Netvibes Share' => 'http://www.netvibes.com/signin?url=%2Fshare%3Furl%3D%2525permalink%2525%26title%3D%2525title%2525%26src%3Dhttp%253A%252F%252Fwww.addtoany.com%252Fadd_to%252FNetvibes_Share%253Flinkurl%253D%252525permalink%252525%2526type%253Dpage%2526linkname%253D%252525title%252525%2526linknote%253D'
// Blogger => https://www.google.com/accounts/ServiceLogin?service=blogger&continue=https%3A%2F%2Fwww.blogger.com%2Floginz%3Fd%3Dhttp%253A%252F%252Fwww.blogger.com%252Fblog_this.pyra%253Ft%2526u%253D%252525permalink%252525%2526l%2526n%253D%252525title%252525%26a%3DADD_SERVICE_FLAG&passive=true&alinsu=0&aplinsu=0&alwf=true&hl=fr&skipvpage=true&rm=false&showra=1&fpui=2&naui=8
