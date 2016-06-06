# ButterBean

ButterBean is a framework for creating cool post meta boxes.

**Development environments only:** Please note that this is a development version of the code.  Don't use on a live site yet.

## Usage

This is a quick guide.  If you're familiar with the WordPress Customization API, you should probably pick this up fairly quickly.  A lot of the same concepts are used here.

### Installation

Drop the `butterbean` folder into your plugin. That's the simple part.

You're only going to want to load this on the edit post screen for whatever post type you're using it on.

        add_action( 'load-post.php',     'th_load' );
        add_action( 'load-post-new.php', 'th_load' );

        function th_load() {

        	// Bail if not our post type.
        	if ( 'your_post_type' !== get_current_screen()->post_type )
        		return;

        	require_once( 'path/to/butterbean/butterbean.php' );
        }

### Registration

There's a built-in action hook called `butterbean_register`.  You're going to use that to register everything.  So, you need to set up a callback function for that.

        add_action( 'butterbean_register', 'th_register', 10, 2 );

        function th_register( $butterbean, $post_type ) {

        	// Register managers, sections, controls, and settings here.
        }

#### Registering a manager

A **manager** is a group of sections, controls, and settings.  It's displayed as a single meta box.  There can be multiple managers per screen (don't try multiples yet).

        $butterbean->register_manager(
        	'example',
        	array(
        		'label'     => esc_html__( 'Example', 'your-textdomain' ),
        		'post_type' => 'your_post_type',
        		'context'   => 'normal',
        		'priority'  => 'high'
        	)
        );

        $manager = $butterbean->get_manager( 'example' );

#### Registering a section

A **section** is a group of controls within a manager.  They are presented as "tabbed" sections in the UI.

        $manager->register_section(
        	'section_1',
        	array(
        		'label' => esc_html__( 'Section 1', 'your-textdomain' ),
        		'icon'  => 'dashicons-admin-generic'
        	)
        );

#### Registering a control

A **control** is essentially a form field. It's the field(s) that a user enters data into.  Each control belongs to a section.  Each control should also be tied to a setting (below).

        $manager->register_control(
        	'abc_xyz', // Same as setting name.
        	array(
        		'type'    => 'text',
        		'section' => 'section_1',
        		'label'   => esc_html__( 'Control ABC', 'your-textdomain' ),
        		'attr'    => array( 'class' => 'widefat' )
        	)
        );

#### Registering a setting

A **setting** is nothing more than some post metadata and how it gets stored.  A setting belongs to a specific control.

        $manager->register_setting(
        	'abc_xyz', // Same as control name.
        	array(
        		'sanitize_callback' => 'wp_filter_nohtml_kses'
        	)
        );

## Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/board/topics), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 60,000+ users (and growing).

## Copyright and License

Various ideas from different projects have made their way into ButterBean.  A few of the projects that had an important impact on the direction of this project are:

* Architecturally, the PHP code was modeled after the core WordPress Customization API. - [GPL 2+](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
* The design concept of the default tabbed interface was taken from [WooCommerce](http://www.woothemes.com/woocommerce/). &copy; WooThemes - [GPL 3+](http://www.gnu.org/licenses/gpl.html)
* Code ideas for the media frame were borrowed from [WP Term Images](https://wordpress.org/plugins/wp-term-images/). &copy; John James Jacoby - [GPL 2+](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

2015-2016 &copy; [Justin Tadlock](http://justintadlock.com).
