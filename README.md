# RepositoryToAnki

Convert a git repository with folders and files into a CSV file that can be imported into Anki

## Requirements

* [Git](https://git-scm.com/)
* [Composer](https://getcomposer.org/)
* A git repository somewhere with card files (look below for details)

## Install

Easy as:
```bash
composer install
```

## Commands

How to "convert" the repository to a CSV file ?
```bash
bin/console export 'urltomyrepo.git'
```

## Format

You have to create files with the "card" extensions.

The files can be in subfolder, as many as you want. The folder's name will be added to the tag list of the card.

Exemple:
```
/tag1/tag2/mycard.card
```
The card will have the tag1 and tag2.

Card content exemple:
```
tags: some,tags,list,comma,separated
-----
My question or some text ?

On many lines.
-----
The response.

Can be on many line too.
```

## The CSV

The file will be generated in the export `/export` by default.

The format is : "question","content","tags"

## Import

In Anki, select the menu "File > Import".
Select the generated CSV.
Click on "deck" then create a new deck or select the deck that you want to be updated by the CSV.
Leave the default options:
* type: basic
* field separator: semicolon
* allow HTML: not checked
* field 1: front
* field 2: back
* field 3: tags
Then click on "import"

DONE

## Authors

- [Florian Belhomme](http://florianbelhomme.com) a.k.a Solune
- [The Community Contributors](https://github.com/florianbelhomme/RepositoryToAnki/graphs/contributors)

## Contribute

**Contributions to the repository are always welcome! Feedback is great.**

Feel free to fork the project and make a PR.

## Support

If you are having problems, fill an [issue](https://github.com/florianbelhomme/RepositoryToAnki/issues).

## License

This bundle is licensed under the [MIT License](http://opensource.org/licenses/MIT)
