# Islandora FITS

## Introduction

A simple module to extend Islandora solution pack install processes by adding
technical metadata extraction via the File Information Tool Set (FITS).

Behind the scenes, the module tries to get as much metadata from your file by
running:

```
$ fits.sh -i *infile* -xc -o *outfile*
```

The -xc command can sometimes cause problems, so if that fails, the module
tries:

```
$ fits.sh -i *infile* -x -o *outfile*
```

Should that fail, technical metadata extraction is aborted and the error is
logged in the watchdog. An error my be produced and logged in the apache
error.log file even if TECHMD DS extraction is successfull, as the first
attempt may fail and log an error while subsequent attempts may succeed.

The most common error printed out to the error.log file that is safe to
ignore is as follows:

```
"Exception in thread "main" java.lang.NullPointerException
...
Error: output cannot be converted to a standard schema format for this file"
```

Watchdog will be updated when TECHMD DS fail's to generate.

## Requirements

This module requires the following modules/libraries:

* [Islandora](https://github.com/discoverygarden/islandora)
* [Tuque](https://github.com/islandora/tuque)
* [The File Information Tool Set](https://github.com/harvard-lts/fits)

Clone the fits tool which can be found
[here](https://github.com/harvard-lts/fits). Make sure it is in a location where
the apache user can get access.  Navigate to the fits folder and make sure
`fits.sh` has executable permissions so the apache user can run the script.


## Installation

Install as
[usual](https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules).

## Configuration

Set the path for `fits.sh` and create a name for the technical metadata stream
ID in Configuration » Islandora » Islandora Utility Modules » FITS Tool
(admin/config/islandora/tools/fits).

![image](https://cloud.githubusercontent.com/assets/2371345/9691525/4a2591f6-5319-11e5-9949-522100689641.png)

## Documentation

Further documentation for this module is available at [our
wiki](https://wiki.duraspace.org/display/ISLANDORA/Islandora+FITS).

## Troubleshooting/Issues

A [known issue](https://jira.duraspace.org/browse/ISLANDORA-2057) with the FITS
module can cause a memory leak as of version 7.x-1.10.

Having problems or solved one? Create an issue, check out the Islandora Google
groups.

* [Users](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora)
* [Devs](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora-dev)

or contact [discoverygarden](http://support.discoverygarden.ca).

## FAQ

Q. Why didn't I get any technical metatadata?

A. If you run an ingest and you don't get any technical metadata, check to make
sure the permissions on the fits folder and the `fits.sh` script are correct and
the apache user can run the script.

Q. Why am I getting weird errors in the log?

A. Some images and audio files will cause problems during metadata extraction.
These are not fatal errors, but appear to be formats the fits script can't
understand. In these cases, you will get some error reporting in the technical
metadata datastream that may help determine what happened.

## Maintainers/Sponsors

Current maintainers:

* [discoverygarden](http://www.discoverygarden.ca)

## Development

If you would like to contribute to this module, please check out the helpful
[Documentation](https://github.com/Islandora/islandora/wiki#wiki-documentation-for-developers),
[Developers](http://islandora.ca/developers) section on Islandora.ca and create
an issue, pull request and or contact
[discoverygarden](http://support.discoverygarden.ca).

## License

[GPLv3](http://www.gnu.org/licenses/gpl-3.0.txt)
