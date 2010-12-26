#!/usr/bin/env python
# -*- coding: utf-8 -*-
# $Id: __init__.py 50 2009-04-19 13:53:00Z urzenia $
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.11
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)

try:
    from setuptools import setup
except ImportError:
    from distutils.core import setup

setup (
    name                = 'BlipApi',
    version             = '0.02.11',
    description         = 'Blip! (http://blip.pl) communication library.',
    author              = 'Marcin Sztolcman',
    author_email        = 'marcin@urzenia.net',
    url                 = 'http://blipapi.googlecode.com',
    packages            = ['blipapi', ],

    long_description    = '''
        Blip! (http://blip.pl) communication library.
    ''',
    classifiers         = [
        'License :: OSI Approved :: GNU Library or Lesser General Public License (LGPL)',
        'Programming Language :: Python',
        'Development Status :: 5 - Production/Stable',
        'Intended Audience :: Developers',
        'Operating System :: OS Independent',
        'Topic :: Internet',
        'Topic :: Software Development :: Libraries :: Python Modules',
    ],
    keywords            = 'blip microblogging library',
    license             = 'GPL',
    install_requires    = [
        'setuptools',
    ],
)

