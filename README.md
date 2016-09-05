# TokenAuthenticationBundle

This bundle allow you to use a token authentication mechanism similar to OAuth but much more simplified.

## Installation

First, enable bundle in your `AppKernel` by adding it to the `registerBundles` method:
```php
...
            new Youshido\TokenAuthenticationBundle\TokenAuthenticationBundle(),
```

Then create provider and firewall under `app/config/security.yml` (if you don't have encoder yet – might as well add it here):
```yaml
security:
    providers:
        api_user_provider:
            id: token_user_provider

    encoders:
        AppBundle\Entity\User: md5

    firewalls:
        api_firewall:
            pattern: ^/.*
            stateless: true
            simple_preauth:
                authenticator: token_authenticator
            provider: api_user_provider
            anonymous: ~

```

Now add your model class name to the service declaration `app/config/config.yml`:
```yaml
token_authentication:
    user_model: "AppBundle\\Entity\\User"
```

