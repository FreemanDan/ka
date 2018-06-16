<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see bootstrap_preprocess_page()
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see bootstrap_process_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
<?php if (variable_get_value('keramartmodule_priceoff')): ?>
    <style>
        #portfolio .portfolio-item .info_container .sub_title span {display: none;} #portfolio .portfolio-item .info_container .sub_title a { margin-top: 60px;}
    </style>
<?php endif; ?>
<header id="navbar" role="banner" class="<?php print $navbar_classes; ?>">
    <div class='container'>
        <div class='row'>
            <div class='col-xs-12 lg header_info'>
                <div class='logo'>

                    <img  src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />

                </div>
                <div class='title_header'>ИЗГОТОВЛЕНИЕ ДЕКОРАТИВНЫХ ФОНТАНОВ С ДОСТАВКОЙ ПО ВСЕЙ РОССИИ</div>
                <div class='phone'><span>Работаем по всей РФ!<br></span>8 (351) 2150062</div>
            </div>
            <div class='hidden-xs col-sm-12 lg order_block'>
                <div class='order_button'>
                    <a id="calltomybtm" class="callback-form" href="http://keramart/cbox/zakaz" onclick="yaCounter823781.reachGoal('CALLBACKCLICK'); return true;">Закажите звонок</a>
                </div>
            </div>
            <div class='col-xs-12 lg menu'>
                <ul class='clearfix'>
                    <li>
                        <a href='/' target="blank">Главная</a>
                        <span>/</span>
                    </li>
                    <li>
                        <a href='#portfolio'>Портфолио</a>
                        <span>/</span>
                    </li>
                    <li>
                        <a href='#doing_decoration'>О Нас</a>
                        <span>/</span>
                    </li>
                    <li>
                        <a href='#price'>Цены</a>
                        <span>/</span>
                    </li>
                    <li>
                        <a href='#maps'>Контакты</a>
                        <span>/</span>
                    </li>
                    <li>
                        <a href='#other_services'>Услуги</a>
                    </li>
                </ul>
            </div>
            <div class='col-xs-12'>
                <?php /*
                  Создаем декоративные фонтаны
                  <br>
                  индивидуально под ваш интерьер
                 */ ?>
                <h1 class="header_text text-center"> Получите эксклюзивный декоративный фонтан ручной работы, изготовленный скульпторами-дизайнерами под Ваш интерьер по цене серийного!</h1>
            </div>
            <div class='col-xs-12 col-md-7 col-md-offset-5 text-left'>
                <div class='sketch'>
                    Эскизный проект по фотографии места установки за 
                    <span></span>
                    часа!
                </div>
            </div>
            <div class='header_img'>
                <img src='<?php print file_create_url('public://images/landing/fontan_header_img.png'); ?>'>
            </div>
            <div class='col-xs-12 col-sm-7 col-sm-offset-5 text-left img_block_pos'>

                <div class='send_application'>
                    <div class="offer_highlight">
                        <?php if (variable_get_value('keramartmodule_priceoff')): ?>
                            <span>
                                Оставьте заявку на бесплатную консультацию дизайнера <b>прямо сейчас</b> и мы <b class="keramart_red">подарим Вам разработку эскизов</b> (визуализацию в интерьере) вашего фонтана!
                            </span>
                            <?php
                        else:
                            $actionnowtitle = variable_get_value('keramartmodule_actionnowtitle');
                            $actiontill = format_date(strtotime(variable_get_value('keramartmodule_actiondatetill')), 'lp');
                            $actionnow = variable_get_value('keramartmodule_actionnow');
                            if ($actionnow == '1' or $actionnow == '2'):
                                ?>
                                <span class="actionnow">До <strong><?php echo $actiontill; ?></strong> действует <strong><?php echo $actionnowtitle; ?></strong> на изготовление фонтанов по акции + бесплатная разработка эскиза!
                                </span>
                            <?php else: ?>
                                <span class="actionnow">До конца недели изготовление эскиза бесплатно!</span>
                            <?php endif; ?>
                            <br>
                            <span>
                                Получите консультацию
                                <br>
                                ведущего дизайнера мастерской!
                                <br>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class='arrow_icon'></div>              
                    <?php
                    $node = node_load(635);
                    webform_node_view($node, 'full');
                    print theme_webform_view($node->content);
                    ?>
                    <?php
                    if (variable_get_value('keramartmodule_priceoff')):
                    else:
                        ?>
                        <div class='info_order'>
                            <span>
                                <?php if ($actionnow == '1' or $actionnow == '2'): ?>
                                    Жмите, мы предоставим 2-3 варианта проекта фонтана на выбор под ваш интерьер!
                                <?php else: ?>
                                    И мы предоставим 2-3 варианта проекта
                                    фонтана на выбор  под ваш интерьер.
                                <?php endif; ?>
                            </span>
                        </div>  
                    <?php endif; ?>        
                </div>
            </div>
        </div>
    </div>
</header>
<?php print render($title_prefix); ?>
<?php print render($title_suffix); ?>
<?php print $messages; ?>
<?php if (!empty($tabs)): ?>
    <?php print render($tabs); ?>
<?php endif; ?>
<?php if (!empty($page['help'])): ?>
    <?php print render($page['help']); ?>
<?php endif; ?>
<?php if (!empty($action_links)): ?>
    <ul class="action-links"><?php print render($action_links); ?></ul>
<?php endif; ?>
<div id='about_company' data-bg='<?php print $base_path . drupal_get_path('theme', 'keramart_bootstrap') . '/images/bg_about_company.jpg'; ?>'>
    <div class='container'>
        <div class='row'>
            <div class='clearfix' id='info_block'>
                <div class='col-xs-6 col-md-3'>
                    <div class='clock_icon'></div>
                    <span>
                        Срок изготовления
                        <br>
                        фонтана
                        <span>от 3 недель</span>
                    </span>
                </div>
                <div class='col-xs-6 col-md-3'>
                    <div class='wallet_icon'></div>
                    <span>
                        Стоимость от
                        <br>
                        <?php if (variable_get_value('keramartmodule_priceoff')): ?>
                            <span>29</span>
                        <?php else: ?>
                            <span>24</span>
                        <?php endif; ?>
                        т.р.
                    </span>
                </div>
                <div class='col-xs-6 col-md-3'>
                    <div class='experience_icon'></div>
                    <span>
                        Опыт создания
                        <br>
                        фонтанов
                        <span>23</span>
                        года
                    </span>
                </div>
                <div class='col-xs-6 col-md-3'>
                    <div class='set_icon'></div>
                    <span>
                        Более
                        <span>5000</span>
                        <br>
                        выполненных работ
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class='container'>
        <div class='row'>
            <div class='order_fantan'>
                <div class='title'>
                    Компоненты качества фонтанов студии "Керам-Арт"
                </div>
                <div class='sub_title'>
                    Что мы делаем для того, чтобы Вы остались довольны результатом? В процессе
                    <br>
                    работы мы высылаем фотоотчеты промежуточных этапов. Перед обжигом
                    <br>
                    фонтана мы убедимся, что Вас всё устраивает!
                    <div class='number_icon'></div>
                </div>
            </div>
            <div class='service_block clearfix'>
                <div class='col-xs-12 lg'>
                    <div class='col-xs-12 col-md-6 service_left'>
                        <div class='col-xs-12'>
                            <div class='cart_icon'></div>
                            <span>2-3 варианта на выбор</span>
                            <p>Оттолкнувшись от дизайна вашего интерьера мы подготовим несколько вариантов исполнения фонтана в виде эскизных проектов.</p>
                        </div>
                        <div class='col-xs-12'>
                            <div class='pice_icon'></div>
                            <span>Вы - соавтор!</span>
                            <p>Учет ваших пожеланий на протяжении всего процесса создания проекта. Поэтому Вы сможете реализовать все свои дизайнерские задумки.</p>
                        </div>
                        <div class='col-xs-12'>
                            <div class='profesional_icon'></div>
                            <span>Профессионализм</span>
                            <p>Наши скульпторы окончили, в частности, Красноярский институт искусств, Мухинский университет, Челябинский художественный институт.</p>
                        </div>
                    </div>
                    <div class='col-xs-12 col-md-6 service_right'>
                        <div class='col-xs-12'>
                            <div class='formula_icon'></div>
                            <span>Природный материал</span>
                            <p>Керамика экологически чистая и долговечная. Из глины, добытой в экологически чистом районе Челябинской области, село Бускуль.</p>
                        </div>
                        <div class='col-xs-12'>
                            <div class='cassa_icon'></div>
                            <span>Доступная цена</span>
                            <p>Стоимость изготовления нашего эксклюзивного фонтана сопоставима со стоимостью серийного из синтетических материалов.</p>
                        </div>
                        <div class='col-xs-12'>
                            <div class='puzzle_icon'></div>
                            <span>Легкость установки</span>
                            <p>Все наши изделия имеют разборную конструкцию. Вес отдельной детали не более 20 кг. Подробная инструкция по установке. Легко перенести на новое место.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='service_botton contactus-form'>
                <a href='/cbox/zakaz' onclick="yaCounter823781.reachGoal('ORDFRMCLICK'); return true;">ЗАКАЗАТЬ ДЕКОРАТИВНЫЙ ФОНТАН</a>
            </div>
        </div>
    </div>
</div>
<div id='portfolio'>
    <div class='title'>ВЫПОЛНЕННЫЕ РАБОТЫ</div>
    <?php print views_embed_view('breath_portfolio', 'block_1', 63); ?>
</div>
<div id="order_fantan" data-bg='<?php print $base_path . drupal_get_path('theme', 'keramart_bootstrap') . '/images/bg_order_fantan.jpg'; ?>'>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg clearfix">
                <div class="title">Вы в нескольких шагах от вашего фонтана!</div>
            </div>
            <div class="col-xs-12 lg info_fantan clearfix">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <div class="eart_icon"></div>
                        <p>
                            Оставьте
                            <br>
                            заявку на сайте
                        </p>
                        <div class="arrow_icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="user_icon"></div>
                        <p>
                            Мы Вам
                            <br>
                            перезваниваем
                        </p>
                        <div class="arrow_icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="sms_icon"></div>
                        <p>
                            Согласовываем
                            <br>
                            Ваши пожелания и бюджет
                        </p>
                        <div class="arrow_icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="photo_icon"></div>
                        <p>
                            Вы можете выслать
                            <br>
                            фото места
                            <br>
                            установки
                        </p><div class="arrow_icon dn"></div>
                        <p></p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="pickcher_icon"></div>
                        <p>
                            Мы отправляем на выбор
                            <br>
                            несколько эскизов
                            <br>
                            фонтана, утверждаем и
                            <br>
                            приступаем к работе
                        </p>
                        <div class="arrow_icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="lent_icon"></div>
                        <p>
                            Высылаем фотоотчеты
                            <br>
                            ключевых этапов работы,
                            <br>
                            утверждаем готовое изделие
                        </p>
                        <div class="arrow_icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="car_icon"></div>
                        <p>
                            Отправляем готовый фонтан
                            <br>
                            транспортной кампанией
                        </p>
                        <div class="arrow_icon"></div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="smile_icon"></div>
                        <p>
                            Вы получаете уникальный,
                            <br>
                            индивидуальный фонтан
                            <br>
                            под ваш интерьер
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="fantan_button contactus-form">
                    <a href="/cbox/zakaz" onclick="yaCounter823781.reachGoal('ORDFRMCLICK'); return true;">ОСТАВИТЬ ЗАЯВКУ</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="conditions">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg conditions_block">
                <div class="col-xs-12 col-md-4">
                    <div class="calendar_icon"></div>
                    <p>
                        Срок изготовления:
                        <br>
                        <span>от 3-х недель</span>
                    </p>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="earts_icon"></div>
                    <p>
                        Срок выполнения эскизного
                        <br>
                        проекта:
                        <span>24 часа</span>
                    </p>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="medal_icon"></div>
                    <p>
                        Гарантия:
                        <span>2 года</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="doing_decoration">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 clearfix">
                <div class="title">Мы делаем декоративные фонтаны с 1997 года</div>
            </div>
            <div class="col-xs-12 lg content_decoration clearfix">
                <img src='<?php print file_create_url('public://images/landing/decoration_img.jpg'); ?>'>
                <div class="info">
                    <div class="intoruce">
                        <span>
                            <span>Керам-Арт</span>
                            - это семейная мастерская-студия супругов - Сергея и
                            <br>
                            Татьяны Манюшко.
                            <br>
                        </span>
                        <span>
                            Сейчас в нашей студии слаженно работают
                            <span>
                                9 профессиональных
                                скульпторов и дизайнеров.
                            </span>
                        </span>
                        <div class="text_design">
                            <p>
                                Я лично слежу за качеством каждого изготовленного
                                декоративного фонтана. И с уверенностью могу сказать, что
                                каждый из них не просто самостоятельное произведение
                                искусства, но и часть того интерьера или дизайна, для
                                оторого он создавался!
                            </p>
                            <span>
                                Татьяна,
                                <span>
                                    ведущий дизайнер.
                                </span>
                            </span>
                            <div class="number_icon"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="our_company">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg">
                <div class="title">НАША КОМАНДА</div>
            </div>
            <div class="col-xs-12 lg">
                <?php print views_embed_view('artists', 'block_1'); ?>
            </div>
        </div>
    </div>
</div>
<div id="advance">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="title">Любая дополнительная комплектация</div>
            </div>
            <div class="col-xs-12 lg">
                <div class="advance_block">
                    <?php print views_embed_view('complect_fontani', 'block'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="features" data-bg='<?php print $base_path . drupal_get_path('theme', 'keramart_bootstrap') . '/images/features_bg.png'; ?>'>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg">
                <div class="title">ОСОБЕННОСТИ НАШИХ ФОНТАНОВ</div>
            </div>
            <div class="col-xs-12 lg features_block">
                <span>
                    Вес отдельной детали
                    <br>
                    не более 20 кг
                </span>
                <span>
                    Светодиодная подсветка
                    <br>
                    на 12 В
                </span>
                <span>
                    Экологический материал -
                    <br>
                    керамика.
                </span>
                <div class="last">
                    <span>
                        Не требуют подвода воды.
                        <br>
                        Нужно лишь время от времени
                        <br>
                        заливать воду.
                    </span>
                    <span>
                        Безотказная малошумная аквариумная
                        <br>
                        помпа обеспечивает непрерывную
                        <br>
                        циркуляцию воды
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="like_our_doing">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg">
                <div class="title">как мы делаем фонтаны</div>
            </div>
            <div class="col-xs-12 lg">
                <div class="like_our_doing_block clearfix row">
                    <div class="col-xs-12 col-md-3">
                        <img class="lazy" data-src="<?php print file_create_url('public://images/landing/like_our_doing_1.jpg'); ?>">
                        <noscript><img src="<?php print file_create_url('public://images/landing/like_our_doing_1.jpg'); ?>"></noscript>
                        <p>Все начинается с уменьшеной модели. В строгом соответствии с проектом.</p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <img class="lazy" data-src="<?php print file_create_url('public://images/landing/like_our_doing_2.jpg'); ?>">
                        <noscript><img src="<?php print file_create_url('public://images/landing/like_our_doing_2.jpg'); ?>"></noscript>
                        <p>Полностью ручная скульптурная формовка будущего фонтана</p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <img class="lazy" data-src="<?php print file_create_url('public://images/landing/like_our_doing_3.jpg'); ?>">
                        <noscript><img src="<?php print file_create_url('public://images/landing/like_our_doing_3.jpg'); ?>"></noscript>
                        <p>Сушка готового изделия - самый ответственный этап</p>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <img class="lazy" data-src="<?php print file_create_url('public://images/landing/like_our_doing_4.jpg'); ?>">
                        <noscript><img src="<?php print file_create_url('public://images/landing/like_our_doing_4.jpg'); ?>"></noscript>
                        <p>Обжиг в печах при температуре более 1000 градусов</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="durable_material">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg">
                <div class="title">экологичный и долговечный материал</div>
            </div>
            <div class="col-xs-12 lg">
                <div class="col-md-6 col-ms-12">
                    <img src="<?php print file_create_url('public://images/landing/durable_material_img.jpg'); ?>">
                </div>
                <div class="col-md-6 col-ms-12">
                    <p>Состав шамотной смеси, используемой в нашей мастерской для изготовления фонтанов является абсолютно уникальны и держится в секрете.</p>
                    <p>В его основе глина, добытая в экологически чистом районе Челябинской области - рядом с селом Бускуль. Глина бускульского месторождения используется в медицине и косметике, продается в аптеках.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id = 'video_review'>
    <div class = "container">
        <div class = "row">
            <div class = "col-xs-12">
                <div class = 'title'>Видео</div>
                <?php
                print views_embed_view('video_nashih_rabot', 'block_1', '691,588,662,637,660,636,630,369');
                ?>
            </div>
        </div>
    </div>
</div>
<div id="price">
    <div class="container">
        <div class="row">
            <?php if (variable_get_value('keramartmodule_priceoff')): ?>
                <div class="col-xs-12 lg">
                    <div class="title">цены</div>
                    <p class="sub_title">Стоимость изготовления фонтана зависит от его конфигурации, наличия дополнительных декоративных элементов и  размеров, поэтому всегда расчитывается индивидуально.</p>
                </div>
                <div class="col-xs-12 lg">
                    <div class="knew_button">
                        <a class="colorbox-node" href="zapros-ceny?width=300&height=470&arrowKey=false" onclick="yaCounter823781.reachGoal('PRFRMCLICK'); return true;">УЗНАЙТЕ СТОИМОСТЬ ВАШЕГО ФОНТАНА</a>
                    </div>
                </div>
            <?php else: ?>


                <div class="col-xs-12 lg">
                    <div class="title">цены</div>
                    <p class="sub_title">Стоимость изготовления фонтана зависит от его конфигурации, наличия дополнительных декоративных элементов и  размеров, поэтому всегда расчитывается индивидуально. Типовые варианты и цены приведены в таблице.</p>
                </div>
                <div class="col-xs-12 lg clearfix">
                    <?php print views_embed_view('prise_2', 'block_1', '99'); ?>
                    <?php if ($actionnow == '1' or $actionnow == '2'): ?>
                        <span class="actionnow">* Обратите внимание, что указанные цены по акции- <b><?php echo $actionnowtitle; ?> на изготовление фонтанов</b> действуют до <strong><?php echo $actiontill; ?></strong>! После чего, действие акции заканчивается!</span>
                        <div class="col-xs-12 lg">
                            <div class="knew_button contactus-form">
                                <a href="/cbox/zakaz" onclick="yaCounter823781.reachGoal('ORDFRMCLICK'); return true;">ПОЛУЧИТЬ СКИДКУ!</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <span class="actionnow">До конца недели изготовление эскиза бесплатно!</span>
                        <div class="col-xs-12 lg">
                            <div class="knew_button contactus-form">
                                <a href="/cbox/zakaz" onclick="yaCounter823781.reachGoal('ORDFRMCLICK'); return true;">ЗАКАЗАТЬ ФОНТАН!</a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>
<div id="you_dis">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 clearfix">
                <div class="title">Дизайнерам</div>
            </div>
            <div class="col-xs-12 clearfix you_dis" data-bg='<?php print $base_path . drupal_get_path('theme', 'keramart_bootstrap') . '/images/dis-work.jpg'; ?>'>
                <div class="row">
                    <div class="col-sm-6 col-xs-12 left_dis">
                        <div class="programma_spec font-size-large uplead">
                            У нас работает программа предоставления выгодных условий сотрудничества для дизайнерских фирм и частных дизайнеров:
                        </div>
                        <div class="uplead">
                            <div class="hsh_icon"></div>
                            <p>Возможность заключения договора на дилерство</p>  
                        </div>
                        <div class="uplead">
                            <div class="pigp_icon"></div>
                            <p>Специальные цены на все наши изделия</p>  
                        </div>
                        <div class="uplead">
                            <div class="copy_icon"></div>
                            <p>Информация о вашей студии на сайте, в портфолио и проектах</p>  
                        </div>
                        <div class="uplead">
                            <div class="mol_icon"></div>
                            <p>Предоставление образцов продукции для ваших клиентов</p>  
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 clearfix font-size-xlarge text-center">
                <p>Реализация в материале любых дизайнерских задумок по вашим эскизам</p>
            </div>
            <div class="col-sm-3 col-xs-6 primer">
                <img src='<?php print file_create_url('public://images/block_dis/dis-ex1.jpg'); ?>'>
                <div class="arrow_icon"></div>
            </div>
            <div class="col-sm-3 col-xs-6 primer">
                <img src='<?php print file_create_url('public://images/block_dis/dis-got1.jpg'); ?>'>
            </div>
            <div class="col-sm-3 col-xs-6 primer">
                <img src='<?php print file_create_url('public://images/block_dis/dis-ex2.jpg'); ?>'>
                <div class="arrow_icon"></div>
            </div>
            <div class="col-sm-3 col-xs-6 primer">
                <img src='<?php print file_create_url('public://images/block_dis/dis-got2.jpg'); ?>'>
            </div>
            <div class="col-xs-12 text-center">
                <div class="lp_button">
                    <a class="colorbox-node" href="zapros-sotrud?width=300&height=470&arrowKey=false" onclick="yaCounter823781.reachGoal('SFRMCLICK');
                            return true;">ОТПРАВЬТЕ ЗАЯВКУ НА СОТРУДНИЧЕСТВО</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="other_services">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg">
                <div class="title">Наши другие услуги</div>
            </div>
            <div class="col-xs-12 lg services_block">
                <?php print views_embed_view('kategorii', 'block_1', '60,61,79,82,68,160'); ?>
            </div>
        </div>
    </div>
</div>
<div id="maps" data-bg='<?php print $base_path . drupal_get_path('theme', 'keramart_bootstrap') . '/images/ka-map.jpg'; ?>'>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 lg">
                <div class="rabota-rf">
                    <a class="colorbox-node" href="http://keramart/help/udobnaya-distancionnaya-rabota-so-vsemi-gorodami-rf?width=700&amp;height=600&amp;arrowKey=false">Работаем со всеми городами РФ!</a>
                </div>
                <div class="info_maps">
                    <p>
                        Контактная информация
                        <br>
                        г. Челябинск, ул Короленко, д. 39/1
                    </p><div class="cel_infp">
                        <div class="phone_icon"></div>
                        <span>8 (351) 2150062</span>
                        <div class="clearfix"></div>
                        <div class="sms_icon"></div>
                        <span><a shref="mailto:keramart@mail.ru">keramart@mail.ru</a></span>
                    </div>
                    <p></p>
                </div>
                <?php
                //$module_name = 'block';   // - имя модуля, который отвечает за реализацию блока.      
                //$block_delta = '16';  // - уникальный идентификатор блока в пределах модуля.
                //$block = block_load($module_name, $block_delta);
                //$block_to_render = _block_get_renderable_array(_block_render_blocks(array($block)));
                //print render($block_to_render);
                ?>
            </div>
        </div>
    </div>     
</div>
<div class="main-container container">
    <div class="row">
        <section<?php print $content_column_class; ?>>
            <?php if (!empty($page['highlighted'])): ?>
                <div class="highlighted jumbotron"><?php print render($page['highlighted']); ?></div>
            <?php endif; ?>
            <a id="main-content"></a>
            <?php print render($page['content']); ?>
        </section>
    </div>
</div>
<footer class="footer container">
    <div class="row">
        <?php print render($page['footer']); ?>
    </div>
</footer>
