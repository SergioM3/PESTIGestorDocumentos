# PESTIGestorArtigos
Gestor de Artigos para Disciplina de PESTI 2022/2023

# Zenodo Document API response example
```json
{
    "conceptrecid": "7847642",
    "created": "2023-04-19T23:25:25.994387+00:00",
    "doi": "10.5061/dryad.tb2rbp039",
    "files": [
        {
            "bucket": "053bf129-2792-4744-8621-e3a80b5e30ae",
            "checksum": "md5:a5857d9ed5b1473bfcc8cf2b67314fa1",
            "key": "README.txt",
            "links": {
                "self": "https://zenodo.org/api/files/053bf129-2792-4744-8621-e3a80b5e30ae/README.txt"
            },
            "size": 1373,
            "type": "txt"
        },
        {
            "bucket": "053bf129-2792-4744-8621-e3a80b5e30ae",
            "checksum": "md5:1a9b4f352aa8f76f6eb2cd619ef70e95",
            "key": "SpiderAnalysis.Rmd",
            "links": {
                "self": "https://zenodo.org/api/files/053bf129-2792-4744-8621-e3a80b5e30ae/SpiderAnalysis.Rmd"
            },
            "size": 57302,
            "type": "rmd"
        },
        {
            "bucket": "053bf129-2792-4744-8621-e3a80b5e30ae",
            "checksum": "md5:80d776a72a349f7e98699b7f06ee88c7",
            "key": "SpiderData.csv",
            "links": {
                "self": "https://zenodo.org/api/files/053bf129-2792-4744-8621-e3a80b5e30ae/SpiderData.csv"
            },
            "size": 220319,
            "type": "csv"
        },
        {
            "bucket": "053bf129-2792-4744-8621-e3a80b5e30ae",
            "checksum": "md5:517d491cd4927f382570a2e81906c629",
            "key": "SpiderPedigree.csv",
            "links": {
                "self": "https://zenodo.org/api/files/053bf129-2792-4744-8621-e3a80b5e30ae/SpiderPedigree.csv"
            },
            "size": 60611,
            "type": "csv"
        }
    ],
    "id": 7847643,
    "links": {
        "badge": "https://zenodo.org/badge/doi/10.5061/dryad.tb2rbp039.svg",
        "bucket": "https://zenodo.org/api/files/053bf129-2792-4744-8621-e3a80b5e30ae",
        "doi": "https://doi.org/10.5061/dryad.tb2rbp039",
        "html": "https://zenodo.org/record/7847643",
        "latest": "https://zenodo.org/api/records/7847643",
        "latest_html": "https://zenodo.org/record/7847643",
        "self": "https://zenodo.org/api/records/7847643"
    },
    "metadata": {
        "access_right": "open",
        "access_right_category": "success",
        "communities": [
            {
                "id": "dryad"
            }
        ],
        "creators": [
            {
                "affiliation": "Research Centre of the Slovenian Academy of Sciences and Arts",
                "name": "Kralj-Fišer, Simona"
            },
            {
                "affiliation": "National Institute of Biology",
                "name": "Kuntner, Matjaž"
            },
            {
                "affiliation": "Hólar University College",
                "name": "Debes, Paul Vincent",
                "orcid": "0000-0003-4491-9564"
            }
        ],
        "description": "<p class=\"MsoNormal\">Sexual dimorphism, or sex-specific trait expression, may evolve when selection favours different optima for the same trait between sexes, i.e., under antagonistic selection. Intra-locus sexual conflict exists when the sexually dimorphic trait under antagonistic selection is based on genes shared between sexes. A common assumption is that the presence of sexual-size dimorphism (SSD) indicates that sexual conflict has been, at least partly, resolved via decoupling of the trait architecture between sexes. However, whether and how decoupling of the trait architecture between sexes has been realised often remains unknown. We tested for differences in architecture of adult body size between sexes in a species with extreme SSD, the African hermit spider (<em>Nephilingis cruentata</em>), where adult female body size greatly exceeds that of males. Specifically, we estimated the sex-specific importance of genetic and maternal effects on adult body size among individuals that we laboratory-reared for up to eight generations. Quantitative genetic model estimates indicated that size variation in females is to a larger extent explained by direct genetic effects than by maternal effects, but in males to a larger extent by maternal than by genetic effects. We conclude that this sex-specific body-size architecture enables body-size evolution to proceed much more independently than under a common architecture to both sexes.</p>",
        "doi": "10.5061/dryad.tb2rbp039",
        "keywords": [
            "Sexual conflict",
            "sexual-size dimorphism",
            "maternal effects",
            "trait architecture"
        ],
        "license": {
            "id": "CC0-1.0"
        },
        "method": "<p>Data from laboratory-reared spiders, and R analysis code as described in the methods part of the manuscript.</p>",
        "notes": "<p>Text editor programmes, MS Excel or Apache OpenOffice Calc open data and pedigree (.csv) files, and RStudio opens the analysis code (.Rmd) file.</p><p>Funding provided by: Javna Agencija za Raziskovalno Dejavnost RS<br>Crossref Funder Registry ID: http://dx.doi.org/10.13039/501100004329<br>Award Number: P1-0236</p><p>Funding provided by: Javna Agencija za Raziskovalno Dejavnost RS<br>Crossref Funder Registry ID: http://dx.doi.org/10.13039/501100004329<br>Award Number: P1-0255</p><p>Funding provided by: Javna Agencija za Raziskovalno Dejavnost RS<br>Crossref Funder Registry ID: http://dx.doi.org/10.13039/501100004329<br>Award Number: J1-9163</p><p>Funding provided by: Javna Agencija za Raziskovalno Dejavnost RS<br>Crossref Funder Registry ID: http://dx.doi.org/10.13039/501100004329<br>Award Number: J1-6729</p>",
        "publication_date": "2023-04-19",
        "relations": {
            "version": [
                {
                    "count": 1,
                    "index": 0,
                    "is_last": true,
                    "last_child": {
                        "pid_type": "recid",
                        "pid_value": "7847643"
                    },
                    "parent": {
                        "pid_type": "recid",
                        "pid_value": "7847642"
                    }
                }
            ]
        },
        "resource_type": {
            "title": "Dataset",
            "type": "dataset"
        },
        "title": "Data and code for: Sex-specific trait architecture in a sexually size dimorphic spider"
    },
    "owners": [
        90070
    ],
    "revision": 1,
    "stats": {
        "downloads": 0,
        "unique_downloads": 0,
        "unique_views": 0,
        "version_downloads": 0,
        "version_unique_downloads": 0,
        "version_unique_views": 0,
        "version_views": 0,
        "version_volume": 0,
        "views": 0,
        "volume": 0
    },
    "updated": "2023-04-19T23:25:27.084318+00:00"
}
