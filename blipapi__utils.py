# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.03
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import mimetypes
import os.path
import random
import urllib

def arr2qstr (arr):
    """ Create urlencoded query string """
    return '&'.join (
        '{0}={1}'.format (
            urllib.quote_plus (k),
            urllib.quote_plus (v, ',')
        ) for k, v in arr.items ()
    )

def gen_boundary ():
    """ Generate uniqe boundary """
    return 'BlipApi.py-'+"".join(random.choice('0123456789abcdefghijklmnopqrstuvwxyz') for x in range(18))

def prepare_post_field (field, value, is_file=False, boundary=None, end=True):
    output = []
    if not boundary:
        boundary = gen_boundary ()
        output.append ('--'+boundary)

    output.append ('Content-Disposition: form-data; name="' + field + '"')
    if is_file:
        output[-1] += '; filename="' + os.path.basename (value) + '"'
        output.append ('Content-Type: ' + (mimetypes.guess_type (value)[0] or 'application/octet-stream'))
        output.append ('')
        with open (value, 'rb') as fh:
            output.append (fh.read ())
    else:
        output.append ('')
        output.append (value)
    output.append ('--' + boundary + ('--' if end else ''))
    output.append ('')
    return ( "\r\n".join (output), boundary, )

