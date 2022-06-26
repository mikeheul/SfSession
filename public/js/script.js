var $collectionHolder,

$addNewItem = $('<a href="#" class="btn btn-info mt-3">Ajouter un module</a>');

$(document).ready(function() {
    // get the collectionHolder
    $collectionHolder = $('#modules_list');

    // append the add new item to the collectionholder
    $collectionHolder.append($addNewItem);

    $collectionHolder.data('index', $collectionHolder.find('.card').length)

    // add remove button to existing items
    $collectionHolder.find('.card').each(function() {
        addRemoveButton($(this));
    });

    // handle click event addnewItem
    $addNewItem.click(function(e) {
        e.preventDefault();
        // create a new form and append to collectionHolder
        addNewForm();
    })
});


// remove item
function addRemoveButton($card) {
    // create remove button
    var $removeButton = $('<a href="#" class="btn btn-danger mb-3"><i class="bi bi-trash"></i></a>');
    var $cardFooter = $('<div class="card-footer"></div>').append($removeButton);

    // handle click event of remove button
    $removeButton.click(function(e) {
        e.preventDefault();
        $(e.target).parents('.card').slideUp(1000, function() {
            $(this).remove();
        })
    });

    $card.append($cardFooter);
}

function addNewForm() {
    
    // getting the prototype
    var prototype = $collectionHolder.data('prototype');
    // get the index
    var index = $collectionHolder.data('index')
    // create the form
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index)
    
    $collectionHolder.data('index', index++);
    
    // create the card
    var $card = $('<div class="card bg-dark"><div class="card-header></div></div>"');
    var $cardBody = $('<div class="card-body"></div>').append(newForm) 
    $card.append($cardBody)

    // append the removeButton to new panel
    addRemoveButton($card);

    $addNewItem.before($card);
    
}