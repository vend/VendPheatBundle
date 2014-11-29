# VendPheatBundle
### Symfony2 Integration for the Pheat feature manager

This bundle integrates Pheat, a feature manager, with Symfony2. It provides
you with the ability to activate features based on dynamic configuration, and
provide them to flexible subsets of your users and tenants.

Still with me?

A typical feature rollout might involve:

- Deploying the feature completely deactivated for everybody
- Allowing some internal developers (with a role assigned) access to the feature
- Allowing some beta testers access to the feature
- Activating the feature for 1% of users, with feedback into your monitoring and analytics systems
- Activating the feature for 10%, 30% then 100% of customers
- Having the feature enabled long-term, but with the ability to turn it off at any time

The idea is to use commits and developer time for as little of this as possible, and to
make the whole thing 'push-button' easy.

To that end, this bundle will provide a UI for feature management that can be integrated into your
backend toolset, and several Symfony-native integration points for the feature manager itself.

## Installation

First, download the bundle. (There's no stable release yet.)

```sh
composer require vend/pheat-bundle "~0"
```

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

## Configuration

The configuration root node is `pheat`:

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

## Credits

* Icon made by [Picol](http://picol.org) from [flaticon.com](http://www.flaticon.com)
  is licensed under [CC BY 3.0](http://creativecommons.org/licenses/by/3.0/)
