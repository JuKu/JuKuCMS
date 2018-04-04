# JuKuCMS

![Rocket CMS](./system/images/logo.png)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FJuKu%2FJuKuCMS.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FJuKu%2FJuKuCMS?ref=badge_shield)

Open Source CMS should be the fastest CMS world wide (WIP).\
[![Waffle.io - Columns and their card count](https://badge.waffle.io/JuKu/JuKuCMS.svg?columns=all)](https://waffle.io/JuKu/JuKuCMS) 
[![Latest Stable Version](https://poser.pugx.org/com.jukusoft/jukucms/version)](https://packagist.org/packages/com.jukusoft/jukucms)
[![Latest Unstable Version](https://poser.pugx.org/com.jukusoft/jukucms/v/unstable)](//packagist.org/packages/com.jukusoft/jukucms)
[![License](https://poser.pugx.org/com.jukusoft/jukucms/license)](https://packagist.org/packages/com.jukusoft/jukucms)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JuKu/JuKuCMS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JuKu/JuKuCMS/?branch=master)

## Name suggestions

  - **provisional name**: JuKuCMS
  - Falcon CMS (falcons are the fastest animals in the world)
  - RocketCMS (as fast as a rocket - the fastest vehicle on the earth)
  - WarpCMS (suggested by @PascalReintjens)
  - HyperspeedCMS (suggested by @PascalReintjens)

## Main Goals

I am one of the earlier developers of [ContentLion](http://contentlion.org) and now i want to build a new - very fast - CMS (Content Management System).\
My favorite CMS is wordpress, but it has a really big problem: Its very slow.\
I think Wordpress is the best, but also the slowest CMS (also with Cache plugins), because it depends on the software & plugin architecture.\
So i try to make it better! Build a fast CMS with all required features by default, but also very extenable.\
**Main goal**: Reach a performance that CMS can **generate pages in <= 200ms**.

  - fastest CMS in then world
  - scalable
  - supports Memcache
  - based on newest technologies (PHP7 and so on)
  - SEO / search engine friendly by default
  - WYSIWYG Editor (What You See Is What You Get, like Word)
  - support for Blog & ticket (support) systems
  - self repair (if database is broken, this CMS should be able to repair the database itself without any manuall interactions)
  - extendable
  - mobile friendly
  - get high Google page speed score
  - plugin marketplace (only checked plugins!) with speed index (how much they reduce speed of cms)
  
## Requirements

  - PHP 7.0.8+
  - MySQL 5.7+
  - Mod_Rewrite Support
  
## Icon

https://www.iconfinder.com/icons/298861/rocket_icon#size=256, MIT License

## Official Wiki & Support

  - [Wiki](https://juku.github.io/JuKuCMS/)
  - [Mail](mailto:info[at]jukusoft.com)

## Caching

Currently, following caches are supported:

  - file cache
  - memcache
  - memcached
  - [Hazelcast](http://hazelcast.org)
  
## Architecture

  - Styles (/styles)
  - Plugins (/plugins)
  - Core
      * Kernel (system/packages)
      * Micro-Kernel (system/core/classes, system/core/driver, system/core/exception)
      
## Roadmap

  - **Core finished** - 04.04.2018 ([Issues](https://github.com/JuKu/JuKuCMS/issues?utf8=%E2%9C%93&q=is%3Aissue+milestone%3ACore+))

## First Screenshots

Some early screenshots from core, **main page**:\
\
![Screenshot Main Page](./docs/images/screenshot1.jpg)\
\
**Admin Area**:\
![Admin Area Screnshot](./docs/images/screenshot2.jpg)

## Presentation

[Slides](http://slides.com/juku/rocketcms/fullscreen)

Badge Links:\
https://poser.pugx.org/

## License

**Styles, Plugins & Packages** can have their own licenses (see LICENSE file in directory)!\
All other code is licensed under **Apache 2.0** license.

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FJuKu%2FJuKuCMS.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FJuKu%2FJuKuCMS?ref=badge_large)