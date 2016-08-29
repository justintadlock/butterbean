# ButterBean

ButterBean is a neat little post meta box framework built on [Backbone.js](http://backbonejs.org) and [Underscore.js](http://underscorejs.org). You can run it as a standalone plugin or drop it into your own plugins.

The idea behind ButterBean came about because I often build custom post types that need quite a bit of custom metadata attached to the posts.  Separating this into multiple meta boxes wasn't fun or user friendly.  So, I decided to go with a single tabbed meta box instead.  

And, that's what ButterBean is.  It's essentially a meta box with tabs for lots of content.

## Just the interface

A lot of meta box frameworks try to do everything.  They handle backend output, frontend output, and everything else you can think of.  ButterBean is meant to be an interface only.  Because every project's needs are vastly different, it doesn't make sense to stick you with a bunch of things you don't need.  This means that the code can stay relatively lean and flexible, which makes it perfect for bundling in your plugins.

So, don't go looking for functions for outputting metadata on the front end from ButterBean.  It doesn't have any.  Use the core WordPress functionality or build your own wrapper functions.

## Documentation

This is a quick guide.  If you're familiar with the WordPress Customization API, you should probably pick this up fairly quickly.  A lot of the same concepts are used here.

### Installation

Drop the `butterbean` folder into your plugin. That's the simple part.

The script will auto-load itself on the correct admin hooks.  You just need to load it like so:

```
add_action( 'plugins_loaded', 'th_load' );

function th_load() {

        require_once( 'path/to/butterbean/butterbean.php' );
}
```

### Registration

There's a built-in action hook called `butterbean_register`.  You're going to use that to register everything.  So, you need to set up a callback function for that.

```
add_action( 'butterbean_register', 'th_register', 10, 2 );

function th_register( $butterbean, $post_type ) {

        // Register managers, sections, controls, and settings here.
}
```

#### Registering a manager

A **manager** is a group of sections, controls, and settings.  It's displayed as a single meta box.  There can be multiple managers per screen (don't try multiples yet).

```
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
```

#### Registering a section

A **section** is a group of controls within a manager.  They are presented as "tabbed" sections in the UI.

```
$manager->register_section(
        'section_1',
        array(
        	'label' => esc_html__( 'Section 1', 'your-textdomain' ),
		'icon'  => 'dashicons-admin-generic'
	)
);
```

#### Registering a control

A **control** is essentially a form field. It's the field(s) that a user enters data into.  Each control belongs to a section.  Each control should also be tied to a setting (below).

```
$manager->register_control(
        'abc_xyz', // Same as setting name.
        array(
        	'type'    => 'text',
        	'section' => 'section_1',
        	'label'   => esc_html__( 'Control ABC', 'your-textdomain' ),
        	'attr'    => array( 'class' => 'widefat' )
        )
);
```

#### Registering a setting

A **setting** is nothing more than some post metadata and how it gets stored.  A setting belongs to a specific control.

```
$manager->register_setting(
        'abc_xyz', // Same as control name.
        array(
        	'sanitize_callback' => 'wp_filter_nohtml_kses'
        )
);
```

### JavaScript API

ButterBean was built using [Backbone](http://backbonejs.org) for handling models, collections, and views.  It uses [Underscore](http://underscorejs.org) for rendering templates for the views.  All output is handled via JavaScript rather than PHP so that we can do cool stuff on the fly without having to reload the page.  This is particularly useful when you start building more complex controls.

You'll never need to touch JavaScript until you need to build a control that relies on JavaScript.

#### The butterbean object

`butterbean` is the global object that houses everything you ever want to touch on the JavaScript side of things.  It's located in the `js/butterbean.js` file.  This file is well-documented, so you'll want to dive into it for doing more advanced stuff.

`butterbean.views.register_control()` is what most people will end up using.  It's a function for registering a custom control view.  New views can be created for each `type` of control.

Here's a quick example of registering a view for a color control where we need to call the core WordPress `wpColorPicker()` function.  It uses the `ready()` function, which is fired after the HTML has been rendered for the view.

```
( function() {

        butterbean.views.register_control( 'color', {

        	// Calls the core WP color picker for the control's input.
                ready : function() {

                        var options = this.model.attributes.options;

                        jQuery( this.$el ).find( '.butterbean-color-picker' ).wpColorPicker( options );
                }
        } );
}() );
```

## Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](http://themehybrid.com/board/topics), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 70,000+ users (and growing).

## Copyright and License

Various ideas from different projects have made their way into ButterBean.  A few of the projects that had an important impact on the direction of this project are:

* Architecturally, the PHP code was modeled after the core WordPress Customization API. - [GPL 2+](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
* The design concept of the default tabbed interface was taken from [WooCommerce](http://www.woothemes.com/woocommerce/). &copy; WooThemes - [GPL 3+](http://www.gnu.org/licenses/gpl.html)
* Code ideas for the media frame were borrowed from [WP Term Images](https://wordpress.org/plugins/wp-term-images/). &copy; John James Jacoby - [GPL 2+](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

2015-2016 &copy; [Justin Tadlock](http://justintadlock.com).
