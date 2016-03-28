<?php

namespace KiWi\DependencyInjection;

use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
//use Easybook\Providers\CodeHighlighterServiceProvider;
//use Easybook\Util\Toolkit;

class Application extends Container
{
    const VERSION = '5.0-DEV';

    public function __construct()
    {
        parent::__construct();

        $app = $this;

        // -- global generic parameters ---------------------------------------
        $this['app.debug'] = false;
        $this['app.charset'] = 'UTF-8';
        $this['app.name'] = 'easybook';
        $this['app.signature'] = <<<SIGNATURE
                     |              |
 ,---.,---.,---.,   .|---.,---.,---.|__/
 |---',---|`---.|   ||   ||   ||   ||  \
 `---'`---^`---'`---|`---'`---'`---'`   `
                `---'
SIGNATURE;

        // -- global directories location -------------------------------------
        $this['app.dir.base'] = realpath(__DIR__.'/../../../');
        $this['app.dir.cache'] = $this['app.dir.base'].'/app/Cache';
        $this['app.dir.doc'] = $this['app.dir.base'].'/doc';
        $this['app.dir.resources'] = $this['app.dir.base'].'/app/Resources';
        $this['app.dir.plugins'] = $this['app.dir.base'].'/src/Easybook/Plugins';
        $this['app.dir.translations'] = $this['app.dir.resources'].'/Translations';
        $this['app.dir.skeletons'] = $this['app.dir.resources'].'/Skeletons';
        $this['app.dir.themes'] = $this['app.dir.resources'].'/Themes';

        // -- console ---------------------------------------------------------
        $this['console.input'] = null;
        $this['console.output'] = null;
        $this['console.dialog'] = null;


        
        // maintained for backwards compatibility
        $this['publishing.id'] = function () {
            trigger_error('The "publishing.id" option is deprecated since version 5.0 and will be removed in the future. Use "publishing.edition.id" instead.', E_USER_DEPRECATED);
        };

        // -- event dispatcher ------------------------------------------------
        $this['dispatcher'] = function () {
            return new EventDispatcher();
        };

        // -- finder ----------------------------------------------------------
        $this['finder'] = $this->factory(function () {
            return new Finder();
        });

        // -- filesystem ------------------------------------------------------
        $this['filesystem'] = $this->factory(function () {
            return new Filesystem();
        });

       

        //$this->register(new PublisherServiceProvider());
        

        

        // -- titles ----------------------------------------------------------
        $this['titles'] = function () use ($app) {
            $titles = Yaml::parse(
                $app['app.dir.translations'].'/titles.'.$app->book('language').'.yml'
            );

            // books can define their own titles files
            if (null !== $customTitlesFile = $app->getCustomTitlesFile()) {
                $customTitles = Yaml::parse($customTitlesFile);

                return Toolkit::array_deep_merge_and_replace($titles, $customTitles);
            }

            return $titles;
        };
    }

    final public function getVersion()
    {
        return static::VERSION;
    }


    /**
     * Appends the given value to the content of the container element identified
     * by the 'id' parameter. It only works for container elements that store arrays.
     *
     * @param string $id    The id of the element that is modified
     * @param mixed  $value The value to append to the original element
     *
     * @return array The resulting array element (with the new value appended)
     */
    public function append($id, $value)
    {
        $array = $this[$id];
        $array[] = $value;
        $this[$id] = $array;

        return $array;
    }

   

   
    
}
