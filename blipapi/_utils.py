# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.11
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/lgpl-3.0.html The GNU Lesser General Public License, version 3.0 (LGPLv3)

import mimetypes
import os.path
import random
import urllib

def arr2qstr (arr, all=False):
    """ Create urlencoded query string """
    return '&'.join (
        '%s=%s' % (
            urllib.quote_plus (str (k)),
            urllib.quote_plus (str (v), ',')
        ) for k, v in arr.items () if all or v
    )

def gen_boundary ():
    """ Generate uniqe boundary """
    return 'BlipApi.py-'+"".join (random.choice ('0123456789abcdefghijklmnopqrstuvwxyz') for i in range (18))

def encode_multipart (fields, boundary=None, sep="\r\n"):
    """ Generate POST query from given data.
        fields - mapping object, keys are name of POST fields, and values:
            - unicode - value of field
            - tuple - first value is file_name, second path to image or file-like object (with specified read () method)
        boundary - if given, use this boundary, instead of generate new
        sep - line separator, defaults to \r\n """

    if type (fields) is not dict:
        fields = dict (fields)
    if not boundary:
        boundary = gen_boundary ()

    output = []
    for k, v in fields.items ():
        output.append ( '--' + boundary )

        ## zwykle pole
        if type (v) in (str, unicode):
            output.append ('Content-Disposition: form-data; name="' + k + '"')
            output.append ('')
            output.append (v.encode ('utf-8', 'ignore'))

        ## plik
        else:
            output.append (
                'Content-Disposition: form-data; name="' + k + \
                '"; filename="' + os.path.basename (v[0]) + '"'\
            )
            output.append ('Content-Type: ' + (mimetypes.guess_type (v[0])[0] or 'application/octet-stream'))
            output.append ('')
            if hasattr (v[1], 'read'):
                output.append (v[1].read ())
            else:
                try:
                    fh = open (v[1], 'rb')
                    output.append (fh.read ())
                finally:
                    fh.close ()

    output.append ( '--' + boundary + '--' )
    output.append ('')
    return (sep.join (output), boundary)

