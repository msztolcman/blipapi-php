# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.04
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import make_post_data

def read (user):
    """ Get specified user's background info """
    if not user:
        raise ValueError ('User name is missing.')

    return dict (
        url     = '/users/' + user + '/background',
        method  = 'get',
    )

def update (background):
    """ Update current user background """
    if not os.path.isfile (background):
        raise ValueError ('Background path is missing or file not found.')

    data, boundary = make_post_data ({ 'background[file]': (background, background, ) })

    return dict (
        url         = '/background',
        method      = 'post',
        boundary    = boundary,
        data        = data,
    )

def delete ():
    """ Delete current user background """

    return dict (
        url     = '/background',
        method  = 'delete',
    )
