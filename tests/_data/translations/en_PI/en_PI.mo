��    .      �  =   �      �     �  	   �  
               
   "  
   0     >  
   G     R  
   W     b     n  
   z     �     �     �     �     �     �  	   �     �     �     �     �       
        ,     ;     L     ]  
   p     ~  
   �     �  
   �     �     �     �  
   �     �     �               
  �       �     �  
   �     �  �   �  �   �     G	     V	  =   [	     �	  8   �	  -   �	  P   
  h   W
     �
  |   �
     Q     ^     d     l  �   �          5  �  G  [   '  o   �  �   �  0   }    �  *   �     �  1   �     %  �   >  "     _   *  �   �  �   {  �     8   �  G   �     '     4     H     P               -                                 .      )                                           
       $   (                  
            +   #   %   &                  	          ,         "   '   *   !          about configure contribute database docker.about docker.browse docker.devbox download home.intro i18n index.crew index.crew2 index.crew3 index.install index.install2 index.intro installation language layouts learn learn.777 learn.composer learn.config learn.config.drop learn.config.registry learn.db learn.folders learn.globally learn.i18n.about learn.i18n.usage learn.install.bone learn.layouts learn.learn learn.logs learn.logs.usage learn.mail learn.mail.hog learn.routes learn.routes.params learn.tagline learn.vhosts logs mail routes visit Project-Id-Version: Bone
POT-Creation-Date: 2018-11-06 18:48+0100
PO-Revision-Date: 2018-11-06 19:43+0100
Last-Translator: Derek McLean <delboy1978uk@gmail.com>
Language-Team: 
Language: en_PI
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Generator: Poedit 1.5.3
X-Poedit-KeywordsList: t;translate;gettext_noop
X-Poedit-Basepath: ../../..
X-Poedit-SourceCharset: UTF-8
X-Poedit-SearchPath-0: src
 About Cap'n's Logs Contribute Database Bone comes wit' a docker-compose.yml in th' project, so ye can instantly get a dev server runnin' if ye use Docker (Tested usin' a default VirtualBox VM). Jus' add this t' yer hosts file Then ye can access th' site at https://awesome.scot in yer browser. O' course if ye don't use docker ye can add it t' yer LAMP stack in th' usual way. Docker Dev Box Loot It be yet another PHP framework swashbucklin' ont' th' scene! Lingo Gaarrrr! We be needin' a foul-mouthed grog swillin' crew t' sail th' ship t' th' fabled Monkey Island. If ye like Bone an' want t' help conquer the seven seas, fork it and get aboard! Ye can download th' Bone MVC source code by sailin' over t' Github. But the Cap'n recommends ye install  'n' do it that way. Be ye wantin an MVC framework peppered with local pirate lingo? It be the most bare bones framework in the seven seas! Garr! Installation Lingo Layouts Learn Bone MVC Framework Make th' data folder writable. 777 be lettin' every landlubber have write access, so if ye can we prefer 775 with yer Apache user in th' group. Ahoy matey! First ye be needin' Th' config folder Ye can drop in any number o' <span class="label label-success">.php</span> files into th'    <span class="label label-success">config/</span> folder. Make sure they return an array wit' th' config    . Ye can o'erride configuration based on environment var    <span class="label label-success">APPLICATION_ENV</span>, so fer instance if th' environment was productionit would load th' additional config th' production subdirectory.</p><p>Thar are several config files by default: In yer config files, ye can add anythin' ye wants. It gets stored in th' Bone\Mvc\Registry. Set yer default db credentials in th' main config/db.php, 'n any environment specific configs in a subdirectory Ye can see ye have a config, data, public, and src folder. Dinnae be touchin' th' vendor folder or th' Cap'n will make ye walk the plank! or if ye haven't installed composer globally ... Bone supports translation into different locales. Translation files (gettext .po 'n .mo) best be placed in data/translations, under a subdirectory o' th' locale, eg data/translations/en_GB/en_GB.po. Ye can set th' default locale 'n an array o' supported locales. T' use th' translator, ye can simply call: Then install Bone. Ignore this config. 'tis ole deprecated nonsense. Learn Bone MVC Framework Bone uses monolog/monolog, 'n logs can be found in <span crew="label label-success">data/logs</span>.            Currently we only support writin' t' files, but ye can add as many channels as ye like: T' use th' logger in a controller: Bone uses Laminas Mail. T' configure th' mail client, jus' drop in yer config (see zend mail docs) If ye be usin' th' Docker Box provided by bone, ye also 'ave th' awesome MailHog at yer disposal. Browse t' awesome.scot:8025 'n ye'll see a catch all email inbox, so ye ne'er needs t' worry about development emails reachin' th' real world. Routes follow a default pattern o' /controller/action/param/value/nextparam/nextvalue/etc/etc<br>Ye can also o'erride routes by definin' them in th' config array: When definin' routes, mandatory variables in th' uri 'ave a colon like :id<br /> Optional uri vars 'ave [ ] surroundin' them like [:id] All our crew be trained by the Melee Island Swordmaster! In yer apache virtual hosts, set the document root as th' public folder Cap'n's Logs Messages in bottles Voyages Visit 