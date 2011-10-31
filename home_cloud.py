#!/usr/bin/env python
# -*- coding: utf-8 -*-

quotes = {u"Is there an active artistic discourse in the Netherlands? " : "http://parallels.schr.fr/report/1-introduction",
u"Artistic research cannot be separated from the practice of making a performance. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-1-process-oriented-product-oriented",
u"Process and product are one and the same thing " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-1-process-oriented-product-oriented",
u"“It is about looking at what exists, what is relevant to include, distilling the concept out of the situation, outlining the essence and going on from there” " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-2-motives-for-research",
u"Artistic research is used to question the choreographer’s own perception of art. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-2-motives-for-research",
u"Research makes it possible to create a transition between theoretical issues and practical matter or the other way around. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-2-motives-for-research",
u"Research deals with unsolved or new questions related to previous work. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-2-motives-for-research",
u"Flow oriented: Intuitive process where the goal is not defined in advance. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-3-orientation-of-research",
u"Orientation is on methods of working, ways to explain, modes of thinking about the material, how reflection can take place. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-3-orientation-of-research",
u"Most of the choreographers conduct their research with a multidisciplinary team of collaborators. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-4-collaboration",
u"In the process of researching many questions are raised and no clear answers are available yet. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-4-collaboration",
u"There is relatively small budget available to do artistic research if it is not connected to a production. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-5-conditions-of-research ",
u"… a place to deepen ones artistic voice. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-5-conditions-of-research",
u"… the lack to have the freedom and/or take the risk to discover that something does not work. " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-6-critical-remarks",
u"“Many times it's more about the idea, the concept rather then the actual choreographic craft” " : "http://parallels.schr.fr/report/3-results/3-1-artistic-research/3-1-6-critical-remarks",
u"Why do choreographers make art, dance, performance? " : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-2-1-participation-and-fragmentation",
u"An active discourse helps to operate more efficiently, as a stronger voice, towards politics and the market. " : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-2-1-participation-and-fragmentation",
u"the discourse is often too philosophical." : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-2-2-interdisciplinary-discourse",
u"There is too little attention for issues related to “the actual craft of making dance”." : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-2-2-interdisciplinary-discourse",
u"There is too little connection between artists and the academic field. " : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-2-2-interdisciplinary-discourse",
u"These peers can serve as a mirror in the creative process. They can help to get more concrete and take the next step in a production.  " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-1-reasons-for-peer-to-peer-exchange",
u"… invite peers to come to rehearsals or showings of work-in-progress. " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-1-reasons-for-peer-to-peer-exchange",
u"An artist can be an outside eye to the other and learn about his ideas and tools at the same time. " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-1-reasons-for-peer-to-peer-exchange",
u"“You should pick your peers” " : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-3-2-who-are-their-peers",
u"Some [choreographers] are in dialogue with artists from other disciplines, others with researchers and academics and dramaturges. " : "http://parallels.schr.fr/report/3-results/3-2-artistic-discourse/3-3-2-who-are-their-peers",
u"“Peer-to-peer exchange is a delicate issue”. " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-3-organization",
u"peer-to-peer exchange should either be organized by artists themselves or not organized at all and thus informal and spontaneous. " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-3-organization",
u"A lack of time seems to be the most important reason for not having enough peer-to-peer exchange.  " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-4-critical-remarks",
u"Peer-to-peer exchange is an investment and you have to give in order to get. " : "http://parallels.schr.fr/report/3-results/3-3-peer-to-peer-exchange/3-3-4-critical-remarks",
u"research is an integral part of what the choreographers see as their professional activity. " : "http://parallels.schr.fr/report/5-conclusion-danslab-and-the-field/5-1-artistic-research",
u"choreographers share a need to not only talk with other choreographers, but with artists from other disciplines, academics and dramaturges as well." : "http://parallels.schr.fr/report/5-conclusion-danslab-and-the-field/5-2-artistic-discourse",
u"How should peer-to-peer exchange be organized?" : "http://parallels.schr.fr/report/5-conclusion-danslab-and-the-field/5-3-peer-to-peer-exchange"}

from django.template.defaultfilters import slugify
from random import sample
from textwrap import fill

import pygraphviz as pgv
G=pgv.AGraph(outputorder="edgesfirst",dpi="48",bgcolor="transparent")
G.node_attr.update(style='filled',fillcolor='white',shape='box')

new_quotes = {}
for quote in quotes.keys():
    new_quotes[fill(quote,50)] = quotes[quote]

quotes = new_quotes

quote_sample = sample(quotes.keys(),10)

def combine(_list):
    _output = []
    for n, linker in enumerate(_list[:-1]):
        for rechter in _list[n+1:]:
            _output.append((linker, rechter))
    return _output

## Pygraphviz can’t transparently handle line breaks:s

for a, b in combine(quote_sample):
    G.add_edge(a.replace("\n","\\n"),b.replace("\n","\\n"))

for node in G.iternodes():
    node.attr['href']= quotes[node.replace("\\n","\n")]

G.layout(prog='fdp')
G.draw('home_cloud_9.png')
G.draw('home_cloud_9.map',format='cmapx')