# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

from _utils import arr2qstr

def read (tag, include=None, since_id=None, limit=10, offset=0):
    """ Get statuses by tag. """

    if not tag:
        raise ValueError ('Tag name is missing.')

    url = '/tags/' + tag

    if since_id:
        url += '/since/' + str (since_id)

    params = dict ()

    if limit:
        params['limit']     = limit
    if offset:
        params['offset']    = offset
    if include:
        params['include']   = ','.join (include)

    if params:
        url += '?' + arr2qstr (params)

    return dict (
        url     = url,
        method  = 'get',
    )

