<?php

/**
 * @file
 * Define functions used only in .install file.
 */

/**
 * Creates the required user roles.
 */
function sosb_install_setup_roles() {
  $anon_role = user_role_load_by_name('anonymous user');
  user_role_grant_permissions($anon_role->rid, array('access content', 'search content', 'use custom search'));
}

/**
 * Define redirect rules.
 */
function sosb_install_setup_redirects() {
  // Add front page redirections.
  if (module_exists('front_page')) {
    variable_set('front_page_enable', 1);
    // variable_set('site_frontpage', variable_get('sosb_admin_app_page_name', ''));

    $rules = array(
      'anonymous user' => array(
        'data' => 'who-are-we',
      ),
    );

      $i = 0;
      foreach ($rules as $role_name => $rule) {
        $role_data = user_role_load_by_name($role_name);
        if (!$role_data) {
          continue;
        }
        $rule['mode'] = 'redirect';
        $rule['filter_format'] = '';
        $rule['rid'] = $role_data->rid;
        $rule['weight'] = $i;
        db_insert('front_page')->fields($rule)->execute();
        $i++;
      }
  }
}

function sosb_install_create_text_formats() {
  // Add text formats.
  $filtered_html_format = array(
    'format' => 'filtered_html',
    'name' => 'Filtered HTML',
    'weight' => 0,
    'filters' => array(
      // URL filter.
      'filter_url' => array(
        'weight' => 0,
        'status' => 1,
      ),
      // HTML filter.
      'filter_html' => array(
        'weight' => 1,
        'status' => 1,
      ),
      // Line break filter.
      'filter_autop' => array(
        'weight' => 2,
        'status' => 1,
      ),
      // HTML corrector filter.
      'filter_htmlcorrector' => array(
        'weight' => 10,
        'status' => 1,
      ),
    ),
  );
  $filtered_html_format = (object) $filtered_html_format;
  filter_format_save($filtered_html_format);

  $full_html_format = array(
    'format' => 'full_html',
    'name' => 'Full HTML',
    'weight' => 1,
    'filters' => array(
      // URL filter.
      'filter_url' => array(
        'weight' => 0,
        'status' => 1,
      ),
      // Line break filter.
      'filter_autop' => array(
        'weight' => 1,
        'status' => 1,
      ),
      // HTML corrector filter.
      'filter_htmlcorrector' => array(
        'weight' => 10,
        'status' => 1,
      ),
    ),
  );
  $full_html_format = (object) $full_html_format;
  filter_format_save($full_html_format);
}

function sosb_install_setup_themes() {
  theme_enable(array('ember', 'nexus'));
  theme_disable(array('bartik'));
  variable_set('theme_default', 'nexus');
  variable_set('admin_theme', 'ember');
  variable_set('node_admin_theme', 1);
  variable_set('theme_ember_settings', array(
    'toggle_logo' => 1,
    'toggle_name' => 1,
    'toggle_slogan' => 1,
    'toggle_node_user_picture' => 1,
    'toggle_comment_user_picture' => 1,
    'toggle_comment_user_verification' => 1,
    'toggle_favicon' => 1,
    'toggle_main_menu' => 1,
    'toggle_secondary_menu' => 1,
    'default_logo' => 1,
    'logo_path' => '',
    'logo_upload' => '',
    'default_favicon' => 0,
    'favicon_path' => 'sites/default/files/images/theme_images/favicon.ico',
    'favicon_upload' => '',
    'display_breadcrumbs' => 0,
    'ember_no_fadein_effect' => 0,
    'favicon_mimetype' => 'image/vnd.microsoft.icon',
  ));
  variable_set('theme_nexus_settings', array(
    'toggle_logo' => 1,
    'toggle_name' => 0,
    'toggle_slogan' => 0,
    'toggle_node_user_picture' => 1,
    'toggle_comment_user_picture' => 1,
    'toggle_comment_user_verification' => 0,
    'toggle_favicon' => 1,
    'toggle_main_menu' => 1,
    'toggle_secondary_menu' => 0,
    'default_logo' => 0,
    'logo_path' => 'sites/all/themes/nexus/logo.jpg',
    'logo_upload' => '',
    'default_favicon' => 0,
    'favicon_path' => 'sites/default/files/images/theme_images/favicon.ico',
    'favicon_upload' => '',
    'breadcrumbs' => 0,
    'slideshow_display' => 1,
    'slide1_head' => '',
    'slide1_desc' => '',
    'slide1_url' => 'node/1',
    'slide2_head' => '',
    'slide2_desc' => '',
    'slide2_url' => 'node/2',
    'slide3_head' => '',
    'slide3_desc' => '',
    'slide3_url' => 'node/3',
    'favicon_mimetype' => 'image/vnd.microsoft.icon',
  ));
}

function sosb_add_content() {
  // Populate the "Translation Application" vocabulary.
  $vocabulary = taxonomy_vocabulary_machine_name_load('jsui_application');
  if (!$vocabulary) {
    return;
  }
  $term = new stdClass();
  $vid = $vocabulary->vid;
  $term->name = 'sosb_bidding' ;
  $term->description = 'sosb jsui bidding app.';
  $term->vid = $vid;
  $term->parent = NULL;
  taxonomy_term_save($term);
}

function sosb_ultimate_cron_settings() {
  variable_set('ultimate_cron_plugin_settings_poorman_settings', array('launcher' => 'serial', 'early_page_flush' => 1, 'user_agent' => 'Ultimate Cron'));
  variable_set('ultimate_cron_plugin_scheduler_default', 'simple');
  variable_set('ultimate_cron_plugin_settings_general_settings', array('nodejs' => 0));
}

function sosb_write_default_settings() {
//   // Site regional settings.
  variable_set('site_default_country', 'RO');
  variable_set('site_default_timezone', 'Europe/Bucharest');
  variable_set('date_default_timezone', 'Europe/Bucharest');
  variable_set('date_first_day', 1); // Monday
  variable_set('configurable_timezones', FALSE);

//   // Default "Basic page" to not be promoted and have comments disabled.
  variable_set('node_options_page', array('status'));
  variable_set('comment_page', COMMENT_NODE_HIDDEN);

//   // Don't display date and author information for "Basic page" nodes by
//   // default.
  variable_set('node_submitted_page', FALSE);

  // Set the 403 and 404 redirect pages.
  variable_set('site_403', '<front>');
  variable_set('site_404', '<front>');

//   // Allow visitor account creation with administrative approval.
  variable_set('user_register', USER_REGISTER_ADMINISTRATORS_ONLY);

//   // Set the jquery version to 1.8 which is the stable version for ember theme
//   // to work with.
  variable_set('jquery_update_jquery_version', '1.8');

  // Social links block settings.
  variable_set('social_media_links_appearance', array('orientation' => 'h', 'show_name' => 0));
  variable_set('social_media_links_icon_style', 'elegantthemes:32');
  variable_set('social_media_links_link_attributes', array('target' => '', 'rel' => ''));
  variable_set('social_media_links_platforms', array(
    'facebook' => array(
      'platform_value' => 'sos.onlus',
      'weight' => '-9',
    ),
    'twitter' => array(
      'platform_value' => 'intent/follow?original_referer=http%3A%2F%2Fwww.sosbambini.org%2F&amp;screen_name=SosBambiniOnlus&amp;tw_p=followbutton&amp;variant=2.0',
      'weight' => '-8',
    ),
    'youtube' => array(
      'platform_value' => 'user/SosBambiniOnlus',
      'weight' => '-3',
    ),
  ));
}

function sosb_generate_content() {
  // return;
  $nodes = array(
    0 => array(
      'content' => '
      <img alt="" src="/sites/default/files/images/ChiSiamoApertura.jpg" style="width:100%;" />
      <p><b>SOS Bambini Onlus:</b></p>
        <ul>
          <li>It aims to improve the living conditions of children from birth through adolescence, with a particular focus on developing countries, helping in particular social structures mostly supporting children (“family home”), centers for abandoned children and young mothers, in locations which will be identified time to time.</li>
          <li>It operates through the development of projects that will lead to medium-term self-sufficiency of the structures involved, respecting the local culture and mentality, in collaboration with existing structures.</li>
          <li>It is based exclusively on the initiative and energy of volunteers, both in terms of time and skills, so that there are no fixed costs and the money raised is used entirely for the ongoing projects.</li>
          <li>It effects from time to time, if it is appropriate to the project, a partnership with other organizations able to perform tasks that SOS Bambini is not able to do alone.</li>
        </ul>',
      'title' => 'WHO WE ARE',
      'link_title' => 'Who we are',
      'alias' => 'who-are-we',
      'promote' => 1,
      'carousel_images' => array('wwa-1.jpg', 'wwa-2.jpg', 'wwa-3.jpg'),
    ),
    1 => array(
               'content' => '<p><b>The activities of the Association take place in:</b></p>
    <p>
        <br />
        <u><strong>ITALY</strong></u> – they are about fund raising to support overseas projects, and activities to support projects of other organizations who need it and that deal with aid to children throughout the country, such as the recruitment and deployment of volunteers.
    </p>
    <p><u><strong>ABROAD</strong></u> with two active projects:</p>
    <ul>
        <li><strong>Romania</strong>: in Sighet and Cluj, we support public and private centers dealing with abandoned children. <em>HOW?:</em>
            <ul>
                <li>Specialistic medical examinations (neurological, eye and dental)</li>
                <li>Sending qualified volunteers (psychologists, psychiatrists, educators)</li>
                <li>Integration of existing staff with qualified local staff to support young mothers, disabled children and troubled teens.</li>
                <li>Sending children to seaside locations, welcome them in summer in italian families.</li>
            </ul>
        </li>
        <li><strong>Guatemala</strong>: in the Barrio El Esfuerzo 1, in partnership with <strong>“Rainbow Guatemala”</strong>, to support a project of literacy and psychosocial support of approximately 30 children living with families in the municipal landfill of Coban.</li>
    </ul>',
      'title' => 'OUR PROJECTS',
      'link_title' => 'Our projects',
      'alias' => 'our-projects',
      'carousel_images' => array('op-1.jpg', 'op-2.jpg', 'op-3.jpg', 'op-4.jpg', 'op-5.jpg'),
    ),
    2 => array(
      'content' => '<p><strong>Sos Bambini Onlus centers:</strong></p>
      <ul>
        <li>&nbsp;Milano, Via Luigi Canonica 72. Tel/Fax 02/34537878</li>
      </ul>
      <ul>
        <li>Menaggio (CO), via Montegrappa 38 – tel. 0344/32356</li>
      </ul>
      <ul>
        <li>Sos Bambini-Romania, Cluj-Napoca, Strada Gruia, 3</li>
      </ul>
      <p><strong>WEB SITE : </strong><a href="http://www.sosbambini.it">http://www.sosbambini.it</a>&nbsp; <a href="http://www.sosbambini.it/english">http://www.sosbambini.it/english</a></p>
      <p><strong>EMAIL : <a href="mailto:info@sosbambini.it">info@sosbambini.it</a></strong></p>',
      'title' => 'CONTACT',
      'link_title' => 'Contact',
      'alias' => 'contact',
      'carousel_images' => array('c-1.jpg', 'c-2.jpg', 'c-3.jpg', 'c-4.jpg', 'c-5.jpg'),
    ),
    3 => array(
      'content' => '
      <h4>You can help us through different methods:</h4>
      <br/>
      <ul>
          <li>Payment on bank account n. 78596384, headed to SOS Bambini Onlus,<br/>Via Canonica 72 , 20154 Milano;</li>
          <li>Bank transfer to SOS Bambini Onlus, at Unicredit Banca , Agenzia 227<br/>P.za Gramsci, Milano, IBAN: IT 10 H 02008 01627 000041097876;</li>
          <li>Bank transfer to SOS Bambini Onlus, at Banca Prossima, Filiale di Milano, <br/>Largo B. Belotti, Milano, IBAN: IT 75 P 033 5901 6001 0000 0016 677</li>
          <li>By credit Card / Paypal:
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
              <input type="hidden" name="cmd" value="_s-xclick" /><input type="hidden" name="hosted_button_id" value="5NYYGR786ZJN2" /><input type="image" alt="PayPal - Il metodo rapido, affidabile e innovativo per pagare e farsi pagare." name="submit" src="https://www.paypalobjects.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" />
            </form>
          </li>
      </ul>',
      'title' => 'SUSTAIN US',
      'link_title' => 'Sustain us',
      'alias' => 'sustain-us',
      'carousel_images' => array('s-1.jpg'),
    ),
  );
  foreach ($nodes as $key => $node) {
    $e = entity_create('node', array('type' => 'page'));
    $e->uid = 1;
    $e->name = 'admin';

    $wrapper = entity_metadata_wrapper('node', $e);
    $wrapper->title->set($node['title']);
    $wrapper->language(LANGUAGE_NONE);
    $wrapper->promote = isset($node['promote']) ? $node['promote']: 0;
    $wrapper->save();

    $nid = $wrapper->getIdentifier();

    $wrapper->body->summary = $wrapper->body->value = $node['content'];
    $wrapper->body->format = 'full_html';

    if (empty($node['carousel_images'])) {
      $node['carousel_images'] = array();
    }
    foreach ($node['carousel_images'] as $i => $path) {
      $file_path = drupal_get_path('module', 'sosb_general') . "/page_corousel_images-bkp/$path";
      $dir = "public://page_corousel_images/$nid";
      if (!drupal_valid_path($dir)) {
        file_prepare_directory($dir, FILE_CREATE_DIRECTORY);
      }
      $file = file_save_data(file_get_contents($file_path), "$dir/$path");
      $wrapper->field_page_carousel[$i]->file->set($file);
      // drupal_unlink($file_path);
    }
    $wrapper->save();

    $fresh_node = node_load($nid);
    node_object_prepare($fresh_node);
    $fresh_node->menu = array(
        'enabled' => 1,
        'mlid' => 0,
        'module' => 'menu',
        'hidden' => 0,
        'has_children' => 0,
        'customized' => 0,
        'options' => array(),
        'expanded' => 0,
        'parent_depth_limit' => 8,
        'link_title' => $node['link_title'],
        'description' => '',
        'parent' => 'main-menu:0',
        'weight' => $key,
        'plid' => 0,
        'menu_name' => 'main-menu'
     );
    $fresh_node->path['pathauto'] = FALSE;
    $fresh_node->path['alias'] = $node['alias'];
    $fresh_node->comment = 1;
    $fresh_node->revision = 0;
    node_save($fresh_node);
  }
}
