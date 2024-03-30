<img src="https://kekse.biz/github.php?draw&override=github:count2">

# **`index`**
This is planned to generate directory listings (atm(!) only via HTTPD).

The plan is to begin with `application/json` output, and then go over `text/html` with
`text/javascript` (e.g. for sorting and other possible processes) and more.

Will use it's own `chroot` (and my [`kekse` extensions](kekse.md)) to secure it up.

