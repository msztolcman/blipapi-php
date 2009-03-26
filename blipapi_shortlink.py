# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.03
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from blipapi__utils import arr2qstr

def read (since_id=None, limit=None, offset=None):
    if since_id:
        url = '/shortlinks/' + str (since_id) + '/all_since'
    else:
        url = '/shortlinks/all'

    params = dict ()

    if limit:
        params['limit'] = str (limit)
    if offset:
        params['offset'] = str (offset)

    if params:
        url += '?' + arr2qstr (params)

    return (url, 'get', None, None)

