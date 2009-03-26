# -*- coding: utf-8 -*-

import os.path

from blipapi__utils import prepare_post_field

def read (user):
    """ Get info about specified user's avatar """
    if not user:
        raise ValueError ('User name is missing.')
    return ['/users/' + str (user) + '/avatar', 'get', None, None]

def update (avatar):
    """ Update current user avatar """
    if not os.path.isfile (avatar):
        raise ValueError ('Avatar path missing or file not found.')

    opts = dict ()

    multipart, boundary = prepare_post_field ('avatar[file]', avatar, is_file=True)

    opts['multipart']   = multipart
    opts['boundary']    = boundary

    return ('/avatar', 'post', None, opts)

def delete ():
    """ Delete current user avatar """
    return ('/avatar', 'delete', None, None)

