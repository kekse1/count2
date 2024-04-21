<img src="https://kekse.biz/github.php?draw&override=github:count2">

[**Back to Index**](../kekse.md)

# `ANSI extends Format`
Currently I'm just **planning** the rest (using (maybe) `Format` w/ `HTML`, `ANSI`, etc..);
but since the most important function in the `ANSI` class is done, I just wanted to describe it,
see below the following '[Features](#features)' section.

## Features
CURRENTLY as follows..

### Styles
* **none** (ANSI reset)
* **bold**
* **faint**
* **italic**
* **underline**
* **blink**
* **inverse**
* **hidden**
* **strike**

### Colors
I only implemented **RGB colors** (even though the first basic ones may follow l8rs?).

//**TODO**/

## Details

### One function for many styles/colors at once
* **`ANSI::style($string, ... $styles)`**

You call it with an optional `$string`, and an arbitrary number of styles or even an array
or two for foreground and background color. If you only want a background color (which would
be the second array), omit the first foreground color array via `null` argument.

This function is maybe important because my ANSI functions will end with a 'ANSI reset' (`[0m`)
after the string/text itself (if defined). This way we combine many styles/colors and close 'em
just once.

### Examples
* `ANSI::bold('bold')`
* `ANSI::color('colored string', [20,240,80], [90,110,130])`
* `ANSI::style('dies ist ein test', 'bold', 'underline', 'blink', [0,200,200], [30, 50, 70], 'strike')`

//**TODO**/
