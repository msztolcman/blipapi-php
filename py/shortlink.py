# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import arr2qstr

def read (code = None, since_id=None, limit=None, offset=None):
    url = '/shortlinks'
    if code:
    	url += '/' + str (code)
    elif since_id:
        url += '/' + str (since_id) + '/all_since'
    else:
        url += '/all'

    params = dict ()

    if limit:
        params['limit'] = str (limit)
    if offset:
        params['offset'] = str (offset)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

