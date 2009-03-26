# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.03
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from blipapi__utils import arr2qstr

def read (user, include=None):
    if not user:
        raise ValueError ('User name is missing.')

    url = '/users/' + str (user)

    params = dict ()

    if include:
        params['include'] = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

