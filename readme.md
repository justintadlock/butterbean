# ButterBean

ButterBean is a framework for creating cool post meta boxes.

**Development environments only:** Please note that this is a development version of the code.  It is still under development.  Don't use on a live site yet.

## Usage

This is a quick guide.  No time to fully explain everything yet.  Please note that what's described here may change without a moment's notice until it's time for a beta.   If you're familiar with the WordPress Customization API, you should probably pick this up fairly quickly.  A lot of the same concepts are used here.

### Installation

Drop the `butterbean` folder into your plugin. That's the simple part.

You're only going to want to load this on the edit post screen for whatever post type you're using it on.

        add_action( 'load-post.php',     'jt_load_post' );
	add_action( 'load-post-new.php', 'jt_load_post' );

        function jt_load_post() {

                // Bail if not your post type.
		if ( 'your_post_type' !== get_current_screen()->post_type )
			return;

		// Load ButterBean.
		require_once( 'path/to/butterbean/butterbean.php' );
        }

### Registration

There's a built-in action hook called `butterbean_register`.  You're going to use that to register everything.  So, you need to set up a callback function for that.

        add_action( 'butterbean_register', 'jt_register' );

        function jt_register( $butterbean ) {

                // You register everything here.
        }

#### Registering a manager

A **manager** is a group of sections, controls, and settings.  It's displayed as a single meta box.  There can be multiple managers per screen (don't try multiples yet).

	$butterbean->register_manager(
                'your_manager_name',
		array(
			'post_type'   => 'your_post_type',
			'context'     => 'normal',
			'priority'    => 'high',
			'label'       => esc_html__( 'Some Label:', 'your-textdomain' )
		)
	);

	$manager = $butterbean->get_manager( 'your_manager_name' );

#### Registering a sections

A **section** is a group of controls within a manager.  They are presented as "tabbed" sections in the UI.

	$manager->register_section(
                'general',
		array(
			'label' => esc_html__( 'General', 'your-textdomain' ),
			'icon'  => 'dashicons-admin-generic'
		)
	);

#### Registering a control

A **control** is essentially a form field. It's the field(s) that a user enters data into.  Each control belongs to a section.  Each control should also be tied to a setting (below).

	$manager->register_control(
                new ButterBean_Control_Text(
                        $manager,
                        'example_text',
                	array(
                		'section'     => 'general',
                		'attr'        => array( 'class' => 'widefat' ),
                		'label'       => esc_html__( 'Some text.', 'your-textdomain' ),
                		'description' => esc_html__( 'Some description.', 'your-textdomain' )
                	)
                )
        );

#### Registering a setting

A **setting** is nothing more than some post metadata and how it gets stored.  A setting belongs to a specific control.

	$manager->register_setting(
                'example_text',
                array(
                        'sanitize_callback' => 'esc_url_raw'
                )
        );

## Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/board/topics), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 60,000+ users (and growing).

## Copyright and License

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

2015-2016 &copy; [Justin Tadlock](http://justintadlock.com).
