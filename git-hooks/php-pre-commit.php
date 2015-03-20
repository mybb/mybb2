#!/usr/bin/env php
<?php

$included = require __DIR__ . '/../vendor/autoload.php';

if (!$included) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL
        . 'curl -sS https://getcomposer.org/installer | php' . PHP_EOL
        . 'php composer.phar install' . PHP_EOL;

    exit(1);
}

// Reference the required classes and the reviews you want to use.
use League\CLImate\CLImate;
use StaticReview\Reporter\Reporter;
use StaticReview\Review\Composer\ComposerLintReview;
use StaticReview\Review\General\LineEndingsReview;
use StaticReview\Review\PHP\PhpCodeSnifferReview;
use StaticReview\Review\PHP\PhpLeadingLineReview;
use StaticReview\Review\PHP\PhpLintReview;
use StaticReview\StaticReview;
use StaticReview\VersionControl\GitVersionControl;

$reporter = new Reporter();
$climate = new CLImate();
$git = new GitVersionControl();

$review = new StaticReview($reporter);

$review->addReview(new LineEndingsReview())
       ->addReview(new PhpLeadingLineReview())
       ->addReview(new PhpLintReview())
       ->addReview(new ComposerLintReview());
//->addReview(new ComposerSecurityReview()); - Can lead to false positives, so currently disabled...

$codeSniffer = new PhpCodeSnifferReview();
$codeSniffer->setOption('standard', 'PSR2');
$review->addReview($codeSniffer);

$review->review($git->getStagedFiles());

if ($reporter->hasIssues()) {
    $climate->out('')->out('');

    foreach ($reporter->getIssues() as $issue) {
        $climate->red($issue);
    }

    $climate->out('')->red('✘ Please fix the errors above.');

    exit(1);
} else {
    $climate->out('')->green('✔ Looking good.')->white('Have you tested everything?');

    exit(0);
}
