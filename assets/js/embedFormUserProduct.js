var $collectionHolder;

var $addTagButton = $('<button type="button" class="btn btn-primary add_file_link m-2">Ajouter un fichier</button>');
var $newLinkLi = $('<div class="row justify-content-center align-items-center"></div>').append($addTagButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags

    if($('#orphan_user_product_files').length) {
        $collectionHolder = $('#orphan_user_product_files');
    }else{
        $collectionHolder = $('div.files');
    }

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addFileForm($collectionHolder, $newLinkLi);
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