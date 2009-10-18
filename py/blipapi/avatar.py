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
    """ Get info about specified user's avatar. """

    if not user:
        url = '/avatar'
    else:
        url     = '/users/' + user + '/avatar',

    return dict (
        url     = url,
        method  = 'get',
    )

def update (avatar):
    """ Update current user avatar. """

    if not os.path.isfile (avatar):
        raise ValueError ('Avatar path missing or file not found.')

    data, boundary = make_post_data ({'avatar[file]': (avatar, avatar,)})

    return dict (
        url         = '/avatar',
        method      = 'post',
        boundary    = boundary,
        data        = data,
    )

def delete ():
    """ Delete current user avatar. """

    return dict (
        url     = '/avatar',
        method  = 'delete',
    )

