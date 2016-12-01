# Export your Kunstmaan Bundles CMS website to static HTML files

## Installation

`composer require nassau/kunstmaan-static-site-bundle`

## Configuration

Configure paths and files you’d like to export using symfony configuration. The below example is the default config:
 
```yaml
# app/config/config.yml

kunstmaan_static_site:

#    # Set your full domain name to be used when generating full URLs:
#    url_prefix: 'http://example.com'
    
    
    # just static files you’d like to export
    files:
        # by default, extract everything in the public "web" folder, excluding PHP files
        web:
            directory: '%kernel.root_dir%/../web'
            exclude: '*.php'

#        # For example, add some custom directory, and store it in generated site under "uploads" path
#        uploads:
#            directory: '%kernel.root_dir%/../uploads'
#            include: '*.jpg'
#            target: 'uploads/'
        

    # those files will be generated using the application:
    routes:
        # all of the published CMS pages
        pages:
            # use this route to access 
            route: '_slug'
            # there is a generator named nodes, and it returns a collection of route params
            # for each nodeTranslation, so a proper url can be generated for it
            generator: 'nodes'
        
        # we’d like to have a sitemap! 
        sitemap_index:
            # this route has only one version, so there is no need to define a generator
            route: 'KunstmaanSitemapBundle_sitemapindex'
            # but there is a required argument to generate url, we provide it here
            defaults:
                _format: 'xml'
            
        # sitemaps for each language
        sitemaps:
            route: 'KunstmaanSitemapBundle_sitemap'
            # there are multiple versions, one for each locale, so a generator is needed
            generator: 'locales'
            # in addition to values provided by the generator, add those values to generate URL:
            defaults:
                _format: 'xml'
            
        # simplest config. one route, one url.
        robots: 
            route: 'KunstmaanSeoBundle_robots'

#       # for example you have a custom contact page with two variants:
#       contact:
#            # this is your route name
#            route: acme_contact
#            # this service needs to generate an array of params for each variant, see below how to do this!
#            generator: acme_contact
```

## Usage

Run `nassau:static-site:dump file://target-directory`. The directory needs to exist.

This will export:
  * all static files (from `web` dir) 
  * all CMS pages
  * Sitemaps and robots file
  * Redirects added in the settings area as well

## Customization

### Storage backends

Only a simple `FileSystemStorage` is provided. Create your own by implementing `Nassau\KunstmaanStaticSiteBundle\Service\Dumper\Storage` interface and tagging your service in the container. The best use case for this would be Amazon S3 or FTP backend.  

The method `storeStaticSite` is called once for the whole site. This way you can export all files in a `zip` or `tar` archive.


#### Example

```php
class DiskPreservingFileSystemStorage implements Storage {
    public function storeStaticSite($location, StaticSiteDumper $dumper) {
        foreach ($dumper->getStaticSite() as $path => $response) {
            if (rand(1,10) > 5) {
                // we’re running out of disk space, let’s skip some files!
                continue;
            }
            
            // do stuff!
        }
    }
}
```

```
# services.yml

services:
    acme.disk_preserving_storage:
        class: DiskPreservingFileSystemStorage
        tags:
          - name: nassau.static_site.storage
            alias: random
            
```

Now you can use this backend using `random://` protocol: `app/console nassau:static-site:dump random://tmp/static-site`

### Route Parameters Generators

Implement the `Nassau\KunstmaanStaticSiteBundle\Service\Generator\RouteParametersGenerator` interface and tag your service in the container.

For example, here’s a fictional `formats` generator that generates a separate url for each of three formats. When used,
given route will be exported three times, each time in different format.

```php
class FormatsGenerator extends RouteParametersGenerator
{
    /**
     * @return array[]
     */
    public function getItems() {
        return [
            ['_format' => 'html'],
            ['_format' => 'json'],
            ['_format' => 'xml'],
        ];
    }
}
```

```
# services.yml

services:
    acme.generators.format:
        class: FormatsGenerator
        tags:
          - name: nassau.static_site.generator
            alias: formats
```
