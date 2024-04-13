<img src="https://kekse.biz/github.php?draw&override=github:count2" />

# **`count2`**
I'm currently planning and working on my newer version of my original [**`count.php`**](https://github.com/kekse1/count.php/),
as [already discussed with the community](https://www.php.de/forum/stellenangebote-und-projektausschreibungen/projekthilfe/1613771-%60count-php%60).

```
Die wichtigsten Punkte werden damit wohl sein: mehrere Dateien, diesmal mit Klassen;
Dateien mit flock() sichern fuer parallele Zugriffe, sowie statt Cookies und IPs diesmal
eher Fingerprinting. Weisz zu letztem Punkt zwar nicht, wie das mit der DSGVO harmoniert,
aber immerhin verzichte ich dann auf die IP-Adressen.. und zuletzt bessere `git`-Nutzung,
sowie uebersichtlichere Dokumentation. ^_^
```

## Documentation
This is just the beginning of the documentation. The project itself is more
important to get finished, but I'm also extending this part from time to time.

Someone suggested me to divide the docs into more than just one big `.md`
markdown document, since my first [`count.php`](https://github.com/kekse1/count.php/) version (with it's docs) was way to complex. Now it's better.

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

### Base structure
I planned this whole repository/fs for more than just the **`count2`** project. Considering this and more, like the
namespace hierarchy, I structured the PHP parts in the `php/` directory this way: everything's below the `kekse`
namespace, which are my base [extensions](docs/kekse.md); the rest is all the apps (like `count2`, and more TODO),
which should have their own directory and namespace below `kekse`, e.g. `kekse\count2`.

See also the [Modules section](#modules) below.

### Modules
Currently there are, beneath the main `kekse` extensions (and the `test` part), the following modules/apps (all
under development, and even more planned yet):

* [**`count2`**](md/count2.md)
* [**`news2`**](md/news2.md)
* [**`blog2`**](md/blog2.md)
* [**`index`**](md/index.md)
* [**`ip`**](md/ip.md)

The plans for bigger (sub) projects do already exist, but I'm keeping 'em (more/less) secret for now, since it's
the most important thing to begin with the main `count2` part!

See also the [base structure](#base-structure) section above. And they all need to fulfill some requirements,
mainly in their structure and the way they use the [`kekse` extensions](docs/kekse.md).

### Directory structure
Most important parts can be found in the `src/` directory (only symlinks).

### Writing the `.md` documentation
* [**Markdown** (hints, etc.)](docs/markdown.md)

# Copyright and License
The Copyright is [(c) Sebastian Kucharczyk](COPYRIGHT.txt),
and it's licensed under the [MIT](LICENSE.txt) (also known as 'X' or 'X11' license).

![kekse.biz](favicon.png)

