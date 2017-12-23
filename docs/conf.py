#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os
import sys
import sphinx_rtd_theme

sys.path.append(os.path.abspath('_exts'))

extensions = []
master_doc = 'index'

project = u'CrispySystem'
copyright = u'2017 Steven Liebregt'

version = '1.1.4'
release = '1.1.4'

html_theme = "sphinx_rtd_theme"
html_theme_path = [sphinx_rtd_theme.get_html_theme_path()]