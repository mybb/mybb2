# How To Contribute

MyBB thrives upon support and patches from the public. In order to keep the contribution process as simple as possible and to ease the lives of core team members, there are a few guidelines that contributors must follow so that we have a chance to keep on top of things.

## Suggesting Features

Feature suggestions should be posted in the [official MyBB 2.0 feature requests forum](http://community.mybb.com/forum-152.html), where they can be discussed fully and team members can provide any help or support to get your feature implemented. Once a feature is accepted, it will be moved to an issue on this GitHub repository, where it will be tagged appropriately and assigned a milestone.

## Reporting Bugs

Bug reports should be posted in the [official MyBB 2.0 bug reports forum](http://community.mybb.com/forum-166.html) or (in the case of small confirmed bugs and users experienced with Git) as in issue within this GitHub repository. Once a bug is confirmed, it will be moved to an issue on this GitHub repository, tagged appropriately and assigned a milestone for completion.

### Reporting Security Issues

Please do not report security issues on GitHub or in the public forums. Instead, please post a topic within the official [Private Inquiries forum](http://community.mybb.com/forum-135.html), where your issue will be discussed in private. THis ensures that security issues are patched in a timely manner without causing harm to existing MyBB installs.

## Submitting Translations

Translations of the core language files are more than welcome. Additional translations ar stored within the [MyBB 2.0 Translations Repository](#TODO) and changes to existing translations or new translations should be submitted against that repository. Translations should not be duplicated - if a translation for your language already exists, please provide contributions to that existing translations et rather than creating your own.

## Submitting Pull Requests

Pull requests may be submitted against this repository, but we request that users follow the [GitHub flow](https://guides.github.com/introduction/flow/) principal of working, and that all pull requests are linked to an existing issue (be it either a bug report or a feature request). Pull requests for features or fixes not already discussed within an existing forum topic or GitHub issue will be closed.

We also ask that pull requests follow our coding standards, as defined below.

### Coding Standards

#### PHP

PHP code must follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) coding style guide. [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) will be ran against all contributions to ensure that code follows this standard. 

In addition to the PSR-2 standard, we have other standards and best practices that must be ahered to:

- All interface names MUST be suffixed with `Interface`. (e.g. `ForumInterface`).
- All abstract class names MUST be prefixed with `Abstract` (e.g. `AbstractForum`).
- All repository class names MUST be suffixed with `Repository` (e.g. `ForumRepository`).
- All factory class names MUST be suffixed with `Factory` (e.g. `ForumFactory`).
- All presenter class names MUST be suffixed with `Presenter` (e.g. `ForumPresenter`).
- The `Interface` suffix MUST take priority over other suffixes. (e.g. `ForumRepositoryInterface`, `ForumFactoryInterface`.
- Getters MUST be used when retrieving the property of a non-Eloquent object.
- Setters MUST be used when manipulating the property of a non-Eloquent object.
- Properties on an object SHOULD have `protected` or `private` visibility.

```php
/**
 * @property string magic
 */
class Foo
{
    /**
     * @var string
     */
    protected $bar;
    
    /**
     * @return string;
     */
    public function getBar()
    {
        return $this->bar;
    }
    
    /**
     * @param string $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }
    
    /**
     * @param string $name
     */
    public function __get($name)
    {
        return 'magic';
    }
}
```

- Methods with a return value and/or arguments MUST have a document block.
- Object properties MUST have a document block with `@var` tag denoting their type.
- Magic properties on an object MUST be declared in a doc block at the top of the class using the `@property` tag.
- Method arguments that are required MUST NOT have a default value.

Should your pull request not follow these standards, you will be requested to reformat your code to suit.

The MyBB team utilise both [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) in order to analyse and correct code style for all contributors. These tools are run as [Git pre-commit hooks](http://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks).

### JavaScript

JavaScript should follow the [JavaScript conventions laid out by Douglas Crockford](http://javascript.crockford.com/code.html).

### SASS/CSS

Core CSS styling is built using the [SASS](http://sass-lang.com) pre-processor. CSS code should follow the [enhanced BEM syntax](http://csswizardry.com/2013/01/mindbemding-getting-your-head-round-bem-syntax/) where fitting, and focus upon providing reusable CSS components rather than replicating styles. CSS should follow [the standards laid out by @mdo](http://codeguide.co/#css).

## Code of Conduct
Contributors to this project should understand and ensure they agree to the terms described in our Code of Conduct available at https://mybb.com/about/conduct/.
