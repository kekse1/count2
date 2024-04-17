<img src="https://kekse.biz/github.php?draw&override=github:count2">

[**Back to Index**](../kekse.md)

# `FileSystem`
Also often uses the [`Path`](path.md) class..

The `FileSystem` class **should really** be instanciated if you access
file system ressources within browser requests. The reason is simple:
the `FileSystem` created some **`chroot`**, so no access outside the
configured root path will be allowed.

//TODO/..

