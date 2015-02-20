# VendPheatBundle

A Symfony bundle that provides integration for Pheat, a dynamic feature
manager.

## Installation
### Step 1: Download the VendPheatBundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```sh
$ composer require vend/pheat-bundle "~0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable it

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Vend\PheatBundle\VendPheatBundle(),
            // ...
        );
    }
}
```

### Step 3: (Optional) Configure the Bundle

The configuration root node is `pheat`.

There's no configuration required to use the feature manager. But you'll
probably want to have at least one feature defined. You can do that in your
`app/config/config.yml` like this:

```yaml
pheat:
    features:
        my_awesome_feature: true
```

Here's an example of a more complex configuration:

```yaml
pheat:
    # Provide initial static feature configuration right in your config.yml
    features:
        # Set features on or off
        active_feature:   true
        inactive_feature: false
        unknown_feature:  null

        # Provide ratios
        ratio_feature:            0.1
        ratio_feature_percentage: 50%
        ratio_feature_full:
            enabled: true
            vary:    username
            ratio:   0.1

        # And variants
        variant_feature:
            enabled: true
            vary:    username
            variants:
                variant_one:   30
                variant_two:   30
                variant_three: default
                variant_four:  10

    # Override the default service classes
    manager:
        class: Pheat\Manager

    context:
        class: Vend\PheatBundle\Pheat\Context

    # Turn off the built-in provider integrations
    providers:
        session: true
        config:  true
```

### Step 4: (Optional) Configure Custom Providers

The bundle comes with a number of preconfigured feature providers. If you'd
like to configure your own, just tag a service that implements
`Pheat\ProviderInterface`:

```yaml
services:
    my_custom_provider:
        class: App\Pheat\MyCustomProvider
        tags:
            -  { name: pheat.provider, priority: 20 }
```

The priority is used to determine the order the providers are managed in. This
is important: you should arrange your feature providers so that the *most specific*
have the highest priorities.

The preconfigured providers all have priorities in the range 0 to 10.

### Step 5: (Optional) Configure the Management Interface

#### Step 5a: Routing

You can see some feature manager information in the Symfony2 profiler. But to
actually write to the configuration, and to see more detail about what's going on,
you'll want to get the optional management interface working. To do so in YAML:

```yaml
# app/config/routing.yml
vend_pheat:
    resource: "@VendPheatBundle/Resources/config/routing/all.xml"
```

Or in XML:

``` xml
<!-- app/config/routing.xml -->
<import resource="@VendPheatBundle/Resources/config/routing/all.xml"/>
```

The default path for the management interface is /features, but you can change that by importing
the routing file `management.xml` instead of `all.xml`, and giving it a different prefix.

#### Step 5b: Security

You'll also want to configure your Symfony security "firewall", because you probably don't want just
anyone seeing the management interface. Your configuration here may vary, but here's how it might look
if you only wanted administrators to be able to use the management UI:

```yaml
# app/config/security.yml
security:
    # ...
    access_control:
        - { path: /features, role: ROLE_ADMIN }
```

#### Step 5c: Customize the Management Layout

The management interface uses the `Resources/views/layout.html.twig` layout in
this bundle by default. It's pretty basic, and won't match your application. We really
want to override this file to make everything look pretty.

The easiest way to do this is to define a template at `app/Resources/VendPheatBundle/views/layout.html.twig`.
Alternatively, you could use [bundle inheritance](http://symfony.com/doc/current/cookbook/bundles/inheritance.html) to
override the template. Here's what it might look like:

```html+jinja
{% extends 'AcmeDemoBundle::layout.html.twig' %}

{% block title %}Acme Demo Application{% endblock %}

{% block content %}
    {% block vend_pheat_content %}{% endblock %}
{% endblock %}
```

(The important bit is to include the `vend_pheat_content` block in your replacement template.)

