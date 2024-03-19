<img src="https://kekse.biz/github.php?draw&override=github:count2" />

# **`count2`**
I'm currently planning and working on my newer version of my original [**`count.php`**](https://github.com/kekse1/count.php/),
as [already discussed with the community](https://www.php.de/forum/stellenangebote-und-projektausschreibungen/projekthilfe/1613771-%60count-php%60).

```
Die wichtigsten Punkte werden damit wohl sein: mehrere Dateien,
diesmal mit Klassen; Dateien mit flock() sichern fuer parallele
Zugriffe, sowie statt Cookies und IPs diesmal eher Fingerprinting.
Weisz zu letztem Punkt zwar nicht, wie das mit der DSGVO harmoniert,
aber immerhin verzichte ich dann auf die IP-Adressen.. und zuletzt
bessere `git`-Nutzung, sowie uebersichtlichere Dokumentation. ^_^
```

## Documentation
Someone gave me this tipp: to separate the 'user manual' from the 'developer pages' or 'internal/technical details'.

That'd make it much easier for my audience. Additionally I just figured out it 'd also be easier to separate nearly
all into even more (markdown) documents, so I just began(!) here.

### Important infos for all users
* [**Installation**](docs/installation.md)
* [**Usage**](docs/usage.md)
* [**Parameters**](docs/parameters.md)
* [**Configuration**](docs/configuration.md)

### For developers and enthusiasts
* [**Technical details**](docs/internals.md)
* [**Modules**](docs/count2.md)
* [**Extensions**](docs/kekse.md)
* [**Coding Guidelines**](docs/guidelines.md)
* [**JSON vectors**](docs/json.md)

#### Writing the `.md` documentation
* [**Markdown** (hints, etc.)](docs/markdown.md)

## Original version(s)
Here's the [last/previous version](original.php/count.php) (and [here's the repo](https://github.com/kekse1/count.php/)),
and there's the [first/original version](original.php/original.php) (more [here](https://github.com/kekse1/count.php/#the-original-version)).

# Copyright and License
The Copyright is [(c) Sebastian Kucharczyk](COPYRIGHT.txt),
and it's licensed under the [MIT](LICENSE.txt) (also known as 'X' or 'X11' license).

![kekse.biz](favicon.png)

