#!/usr/bin/env python2.5
# -*- coding: utf-8 -*-

from __future__ import with_statement
import os, os.path
import sys
import re
import urllib2

class BlipApi (object):
	def __init__ (self, login=None, passwd=None):
		"""BlipApi constructor
		@param string login
		@param string passwd"""

		self._login		= login
		self._password	= passwd

		self._uagent	= 'BlipApi/0.1 (http://repo.urzenia.net/Python:Blipapi)'
		self._referer	= 'http://urzenia.net'
		self._root		= 'http://api.blip.pl'
		self._format	= 'application/json'
		self._timeout	= 10
		self._debug		= False
		self._parsers	= {
			'application/xml':	'simplexml_load_string',
			'application/json':	'json_decode',
		}

	def __del__ (self):
		"""BlipApi destructor
		Close urllib2 handler, if active"""

		pass

	def __setattr__ (self, key, value):
		"""Setter for some options
		For specified keys, call proper __set_* method. Raises Exception exception when incorrect key was specified.
		@param string key name of property to set
		@param mixed value value of property"""

		if key not in ('debug', 'format', 'uagent', 'referer', 'timeout'):
			raise AttributeError ('Unknown param: "%s".' % (key, ), -1);
		return getattr (self, '__set_'+str (key)) (value)

	def __getattr__ (self, key):
		"""Getter for some options
		For specified keys, return them. Raises Exception exception when incorrect key was specified.
		@param string key name of property to return"""

		if key not in ('debug', 'format', 'uagent', 'referer', 'timeout'):
			raise AttributeError ('Unknown param: "%s".' % (key, ), -1);
		return getattr (self, '_'+str (key))

	def __set_debug (self, enable):
		"""Setter for _debug property
		@param bool enable
		@access protected"""

		self._debug = enable ? True : False

	def __set_format (self, format):
		"""Setter for _format property
		Format have to be string in mime type format. In other case, there will be prepended 'application/' prefix.
		@param string format
		@access protected"""

		if format and '/' not in format:
			format = 'application/' . format

		self._format = format

	def __set_uagent (self, uagent):
		"""Setter for _uagent property
		@param string uagent
		@access protected"""

		self._uagent = uagent

	def __set_referer (self, referer):
		"""Setter for _referer property
		@param string referer
		@access protected"""

		self._referer = referer

	def __set_timeout (self, timeout):
		"""Setter for _timeout property
		@param string timeout
		@access protected"""

		self._timeout = timeout

	def connect (self, login=None, passwd=None):
		if login is not None:
			self._login = login
		if passwd is not None:
			self._password = passwd

		http_handler	= urllib2.HTTPHandler (debuglevel = self._debug ? 1 : 0)
		opener			= urllib2.build_opener (http_handler)


		# uwierzytelnainie jeśli mamy hasło i login
		if self._login and self._password is not None:
			passwdmgr = urllib2.HTTPPasswordMgrWithDefaultRealm ()
			passwdmgr.add_password (None, self._root, self._login, self._password)
			opener.add_handler (urllib2.HTTPBasicAuthHandler(passwdmgr))

		self._ch = opener


