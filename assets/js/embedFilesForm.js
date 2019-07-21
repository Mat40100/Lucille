var $collectionFileHolder;

var $addFileButton = $('<button type="button" class="btn btn-primary add_file_link m-2">Ajouter un fichier</button>');
var $newLinkfile = $('<div class="row justify-content-center align-items-center"></div>').append($addFileButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags

    if($('#orphan_user_product_files').length) {
        $collectionFileHolder = $('#orphan_user_product_files');
    }else{
        $collectionFileHolder = $('div.files');
    }

    // add the "add a tag" anchor and li to the tags ul
    $collectionFileHolder.append($newLinkfile);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionFileHolder.data('index', $collectionFileHolder.find(':input').length);

    $addFileButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addFileForm($collectionFileHolder, $newLinkfile);
    });
});

function addFileForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    $collectionHolder.attr('class', 'files row col-12  flex-column align-items-center justify-content-center');

    deleteButton = $('<button class="btn btn-danger">Supprimer</button>');

    var $newFormLi = $('<li></li>').append(newForm);

    $newFormLi = $newFormLi.children(0).append(deleteButton);
    $newFormLi.attr('class', 'row justify-content-center m-2');

    $newLinkLi.before($newFormLi);

    deleteButton.on('click', function(e){
        $(this).parent().remove();
    })
}

$('.delete').on('click', function(e){
   $(this).parent().remove();
});