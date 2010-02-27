# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.05
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import os.path

from _utils import make_post_data

def read (user=None):
    """ Get specified user's background info. """

    if not user:
        url = '/background'
    else:
        url = '/users/' + user + '/background'

    return dict (
        url     = '/users/' + user + '/background',
        method  = 'get',
    )

def update (image):
    """ Update current user background. """

    if not os.path.isfile (image):
        raise ValueError ('Background path is missing or file not found.')

    data, boundary = make_post_data ({ 'background[file]': (image, image, ) })

    return dict (
        url         = '/background',
        method      = 'post',
        boundary    = boundary,
        data        = data,
    )

def delete ():
    """ Delete current user background. """

    return dict (
        url     = '/background',
        method  = 'delete',
    )

