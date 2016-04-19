# DocPad Configuration File
# http://docpad.org/docs/config
moment = require('moment')

# Define the DocPad Configuration
docpadConfig = {
  collections:
    # set up a collection consisting solely of posts only, for proper paging on the index page
    posts: -> @getCollection("html").findAllLive({relativeOutDirPath: {$beginsWith: 'post'}},[{date:-1}]).on('add', (document) -> document.setMetaDefaults(layout: 'post'))
    # set up a collection of documents that does not include 'unclean' metadata - this is so we can
    # prevent the cleanurls plugin from messing with certain pages.  Specifically, the contact form.
    cleanurls: -> @getDatabase().findAllLive({unclean: { $exists: false }})

  templateData:
    # Date handling
    postNiceDateTime: (date, format="MMMM DD, YYYY - h:mm a") -> return moment(date).format(format)
    postNiceDate: (date, format="MMMM DD, YYYY") -> return moment(date).format(format)

  plugins:
    cleanurls:
      collectionName: 'cleanurls'
      static: true
    tags:
      extension: '.html'
      injectDocumentHelper: (document) ->
        document.setMeta({ layout: 'tag' })
}

# Export the DocPad Configuration
module.exports = docpadConfig
