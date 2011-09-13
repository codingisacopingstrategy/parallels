blog_tags = [["artistic development", "artistic research", "artistic team", "choreographers", "contemporary dance", "decisions", "discourse", "dramaturgy", "independent initiative", "insight", "open exchange", "possibilities", "refreshing view", "the hague", "the netherlands", ],
["Andrea Boll", "Andrea Bozic", "Anouk van Dijk", "art schools", "artistic research", "choreographers", "current conditions", "dance academy", "David Weber-Krebs", "Duda Paiva", "Gabriela Tarcha", "Ibrahim Quraishi", "independent consultant", "interviews", "Keren Levi", "Liat Magnezy", "Liat Waysbort", "Mor Shani", "production houses", "research groups", "short description", "time and space", ],
["artistic research", "choreographers", "conclusion", "conclusions", "cross section", "discourse", "innovation", "insight", "landscape", "obstacles", "possibilities", "short description", "similarities and differences", "the netherlands", "visions", ],
["academics", "artistic knowledge", "artistic research", "artistic voice", "assumptions", "choreographers", "colleagues", "dance field", "dancers", "discourse", "everyday practice", "methodologies", "methodology", "personal signature", "presuppositions", "reflection", "self organization", "space and time", "voices", ],
[],
["artistic research", "choreographers", "clarification", "collaborators", "different perspectives", "orientation", "parallels", ],
["aims", "artistic research", "choreographers", "choreography", "clarity", "creative process", "new insights", "paragraphs", "parameters", "research mode", "trajectory", "windows of perception", ],
["artist talks", "artistic research", "boundaries", "choreographer", "choreographers", "creative process", "dance", "disciplines", "everyday realities", "image subject", "motivation", "new perspective", "paradox", "perception", "personal development", "personal interests", "refreshing view", "research topics", "subject matter", ],
["artistic development", "artistic research", "choreographer", "choreographers", "choreography", "general idea", "interdisciplinary team", "intimate group", "orientation", "orientations", "paragraph", "parameters", "period of time", "research processes", "research questions", "research target", "research topic", "space and time", "straight line", ],
["aesthetics", "affinity", "assumptions", "choreographers", "collaborators", "collective knowledge", "core group", "core team", "creative process", "different perspectives", "financial possibilities", "intensity", "multidisciplinary team", "philosophers", "set designers", "theatre directors", "theory and practice", "trajectory", "visual artists", "vulnerability", ],
["academic environment", "artist in residence", "artistic endeavours", "artistic research", "artistic voice", "choreographer", "choreographers", "initiative", "initiatives", "insight", "institutions", "international exchange programs", "motivation", "paragraph", "possibilities", "present conditions", "production houses", "research questions", "research space", "subsidy", ],
["artistic development", "audience", "choreographer", "choreographers", "choreographies", "creation time", "critical remarks", "dancers", "fear of failure", "freedom", "production budgets", "repetition", "risk", "subsidy", "tension", ],
["choreographers", "different perspectives", "discipline", "discourse", "paragraph", ],
["choreographers", "coherent field", "dance artists", "dance performance", "desire", "different reasons", "educations", "fruitful exchange", "initiatives", "lack of time", "open discourse", "personal questions", "possibilities", "self organization", "subgroups", "tendency", "tradition", "working with colleagues", ],
["academic field", "artistic disciplines", "artistic value", "audience", "choreographer", "choreographers", "critique", "different perspectives", "educational system", "further research", "gap", "interdisciplinary discourse", "interdisciplinary group", "new ways", "philosophical issues", "political structures", "practicality", "universities in the netherlands", ],
["academics", "artistic exchange", "artistic research", "choreographers", "dancers", "disciplines", "exchange ideas", "peers", "rehearsals", "safe haven", "works in progress", ],
["choreographers", "collaborators", "peer group", "peers", ],
["audience", "choreographers", "collaborators", "craftsmanship", "creative process", "dialogue", "discourse", "insight", "methodologies", "mirror", "new tools", "peer groups", "peers", "rehearsals", "showings", "specific project", "work in progress", ],
["choreographers", "collaboration", "delicate issue", "dialogue", "discourse", "peers", "thoughts and ideas", "vulnerable position", ],
["choreographers", "critical remarks", "generosity", "having time", "lack of time", "peers", "time issue", ],
["artistic research", "choreographers", "dance field", "diversity", "extent", "initiative", "interviewer", "research location", "research opportunities", "rough sketch", "schneider", "subject matter", "the netherlands", "word of mouth", ],
["artistic research", "choreographers", "conclusion", "conclusions", ],
["artistic development", "basic questions", "choreographers", "continuous processes", "craftsmanship", "discrepancy", "freedom", "orientation", "possibilities", "professional activity", "professional collaborations", "research group", ],
["academics", "choreographers", "dance field", "desire", "disciplines", "discourse", "discourses", "open organization", "paragraph", "peer group", "willingness", ],
["aesthetics", "affinity", "artistic exchange", "artistic ideas", "assumption", "choreographers", "collaboration", "collaborators", "creative environment", "observation", "open communication", "peer group", "peers", "resemblance", "sheer fact", ],
["artistic research", "audience", "choreographer", "choreographers", "doubts", "life time", "main objective", "research documentation", "showings", "trajectory", "variation", ],
["artistic exchange", "artistic research", "choreographers", "collaboration", "continuity", "dance field", "professional dance", "the netherlands", "transparency", ],
["artistic research", "choreographers", "complexity", "diversity", "focus points", "insight", "objective", "orientations", "possibilities", "research orientation", "the netherlands", ],
["academic partners", "choreographers", "collaboration", "disciplines", "discourse", "further research", "general discussion", "interaction", "interdisciplinary group", "interesting topics", "peers", "practicality", "research presentations", "research questions", "research topics", "researches", "specific research", "threshold", ],
["ambassadors", "artistic research", "choreographer", "choreographers", "colleagues", "creative process", "discourse", "former members", "motivation", "possibilities", "profession", "self organization", "word of mouth", ],
["artistic development", "artistic research", "choreographer", "choreographers", "collective knowledge", "continuous exchange", "continuous research", "core group", "dance artists", "dance field", "different places", "knowledge development", "knowledge sharing", "meeting point", "production processes", "research exchange", "research processes", "time and space", "written reflection", ],
["archiving", "artistic research", "audience", "choreographer", "choreographers", "freedom", "insight", "perception", "permeability", "transparency", ],
["artistic research", "dance field", "discourse", "eagerness", "independent platform", "initiative", "innovation", "insight", "interesting topics", "open attitude", "parallels", "perspectives", "possibilities", "refreshing view", ],
[],
[],
["Andrea Boll", "Andrea Bozic", "Anouk van Dijk", "David Weber-Krebs", "Duda Paiva", "Gabriela Tarcha", "Ibrahim Quraishi", "Keren Levi", "Liat Magnezy", "Liat Waysbort", "Mor Shani", ],
[],
[],
[],
[],
[],
[],
]


import pygraphviz as pgv
G=pgv.AGraph(outputorder="edgesfirst")

def combine(_list):
    _output = []
    for n, linker in enumerate(_list[:-1]):
        for rechter in _list[n+1:]:
            _output.append((linker, rechter))
    return _output

for article_tags in blog_tags:
    for a, b in combine(article_tags):
        G.add_edge(a,b)

G.node_attr.update(style='filled',fillcolor='white')
G.layout(prog='fdp')
G.draw('a_cloud.svg')