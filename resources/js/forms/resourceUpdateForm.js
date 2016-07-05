/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/

$(function(){



    //bind all of the inputs

    $("#submitProductChanges").click(function () {
        submitProductForm();
    });


    //do submit if enter is hit
    $('#titleText').keyup(function(e) {
        if(e.keyCode == 13) {
            submitProductForm();
        }
    }); 

    //do submit if enter is hit
    $('#parentResourceName').keyup(function(e) {
        if(e.keyCode == 13) {
            submitProductForm();
        }
    }); 


    $('#isbnOrISSN').keyup(function(e) {
        if(e.keyCode == 13) {
            submitProductForm();
        }
    }); 


    $('#resourceFormatID').keyup(function(e) {
        if(e.keyCode == 13) {
            submitProductForm();
        }
    });

    $('#resourceTypeID').keyup(function(e) {
        if(e.keyCode == 13) {
            submitProductForm();
        }
    });

    // @annelhote : Hide resourceLogo input if empty
    if($('#resourceLogoFileName').val() == '') {
        $('.resourceLogoFileName').hide();
    }

    // @annelhote : Upload ressource logo
    new AjaxUpload('resourceLogo',
        {action: 'ajax_processing.php?action=uploadLogo',
            name: 'resourceLogo',
            onComplete : function(data, response) {
                var errorMessage = $(response).filter('#error');
                if (errorMessage.size() > 0) {
                    $('.resourceLogoFileName').hide();
                    console.log('error during file upload');
                } else {
                    $('.resourceLogoFileName').show();
                    $('#resourceLogoFileName').val($(response).filter('#fileName').text());
                }
            }
        }
    );

    // @annelhote : Remove resource logo
    $('.removeLogo').click(
        function() {
            $.ajax({
                type:       "POST",
                url:        "ajax_processing.php?action=removeResourceLogo",
                cache:      false,
                data:       { resourceID: $("#editResourceID").val() },
                success:    function(html) {
                    $('.resourceLogoFileName').hide();
                    $('#resourceLogoFileName').val('');
                }
            });
        }
    );

    // @annelhote : Add a datePicker on the publication date
    $("#publicationDate").datePicker();

    // @annelhote : if "published" is checked, display the publication comment and the publication date
    if($('#published').attr('checked')) {
        $('.publicationComment').hide();
        $('.publicationDate').hide();
    } else {
        $('.publicationComment').show();
        $('.publicationDate').show();
    }
    $('#published').click(
        function() {
            if($('#published').attr('checked')) {
                $('.publicationComment').hide();
                $('.publicationDate').hide();
            } else {
                $('.publicationComment').show();
                $('.publicationDate').show();
            }
        }
    );

    $("input[name='parentResourceName']").autocomplete('ajax_processing.php?action=getResourceList', {
        minChars: 2,
        max: 20,
        mustMatch: false,
        width: 179,
        delay: 10,
        matchContains: true,
        formatItem: function(row) {
            return "<span style='font-size: 80%;'>" + row[0] + "</span>";
        },
        formatResult: function(row) {
            return row[0].replace(/(<.+?>)/gi, '');
        }
    });

    //once something has been selected, change the hidden input value
    $("input[name='parentResourceName']").result(function(event, data, formatted) {
        inputid = $(this).next();

        if (data[1] != $("#editResourceID").val()){
            inputid.val(data[1]);
            $(this).next().next().html('');
        }else {
            $(this).next().next().html("<br />"+_("Error - Parent cannot be the same as the child"));
        }
    });

    $(".organizationName").autocomplete('ajax_processing.php?action=getOrganizationList', {
        minChars: 2,
        max: 20,
        mustMatch: false,
        width: 164,
        delay: 10,
        matchContains: true,
        formatItem: function(row) {
            return "<span style='font-size: 80%;'>" + row[0] + "</span>";
        },
        formatResult: function(row) {
            return row[0].replace(/(<.+?>)/gi, '');
        }

    });


    //once something has been selected, change the hidden input value
    $(".organizationName").result(function(event, data, formatted) {
        $(this).parent().children('.organizationID').val(data[1]);
    });



    //the following are all to change the look of the inputs when they're clicked
    $('.changeDefault').live('focus', function(e) {
        if (this.value == this.defaultValue){
            this.value = '';
        }
    });

    $('.changeDefault').live('blur', function() {
        if(this.value == ''){
            this.value = this.defaultValue;
        }
    });


    $('.changeInput').addClass("idleField");

    $('.changeInput').live('focus', function() {


        $(this).removeClass("idleField").addClass("focusField");

        if(this.value != this.defaultValue){
            this.select();
        }

    });


    $('.changeInput').live('blur', function() {
        $(this).removeClass("focusField").addClass("idleField");
    });




    $('select').addClass("idleField");
    $('select').live('focus', function() {
        $(this).removeClass("idleField").addClass("focusField");

    });

    $('select').live('blur', function() {
        $(this).removeClass("focusField").addClass("idleField");
    });


    $('.changeAutocomplete').live('focus', function() {
        if (this.value == this.defaultValue){
            this.value = '';
        }

    });


    $('.changeAutocomplete').live('blur', function() {
        if(this.value == ''){
            this.value = this.defaultValue;
        }
    });

    $('textarea').addClass("idleField");
    $('textarea').focus(function() {
        $(this).removeClass("idleField").addClass("focusField");
    });

    $('textarea').blur(function() {
        $(this).removeClass("focusField").addClass("idleField");
    });


    $(".removeParent").live('click', function () {
        $(this).parent().fadeTo(400, 0, function () { 
            $(this).parent().remove();
        });
        return false;
    });



    $(".remove").live('click', function () {
        $(this).parent().parent().parent().fadeTo(400, 0, function () { 
            $(this).remove();
        });
        return false;
    });



    $(".addAlias").live('click', function () {

        var typeID = $('.newAliasTable').children().children().children().children('.aliasTypeID').val();
        var aName = $('.newAliasTable').children().children().children().children('.aliasName').val();

        if ((aName == '') || (aName == null) || (typeID == '') || (typeID == null)){
            $('#div_errorAlias').html(_("Error - Both fields are required"));
            return false;

        }else{
            $('#div_errorAlias').html('');

            //first copy the new alias being added
            var originalTR = $('.newAliasTR').clone();

            //next append to to the existing table
            //it's too confusing to chain all of the children.
            $('.newAliasTR').appendTo('.aliasTable');

            $('.newAliasTR').children().children().children('.addAlias').attr({
                src: 'images/cross.gif',
                alt: _("remove this alias"),
                title: _("remove this alias")
            });
            $('.newAliasTR').children().children().children('.addAlias').addClass('remove');
            $('.aliasTypeID').addClass('changeSelect');
            $('.aliasTypeID').addClass('idleField');
            $('.aliasTypeID').css("background-color","");
            $('.aliasName').addClass('changeInput');
            $('.aliasName').addClass('idleField');


            $('.addAlias').removeClass('addAlias');
            $('.newAliasTR').removeClass('newAliasTR');

            //next put the original clone back, we just need to reset the values
            originalTR.appendTo('.newAliasTable');
            $('.newAliasTable').children().children().children().children('.aliasTypeID').val('');
            $('.newAliasTable').children().children().children().children('.aliasName').val('');


            return false;
        }
    });


    $(".addIsbn").live('click', function() {
        var newIsbn = $('.isbnOrISSN_new').clone();
        newIsbn.removeClass('isbnOrISSN_new');
        newIsbn.appendTo('#existingIsbn');
        $("#existingIsbn").append('<br />');
        $('#newIsbn input').val('');
    });

});

$(".addParent").live('click', function() {

    var parentID = $("#newParent .oneParent input[name='parentResourceID']'").val();
    var parentName = $("#newParent .oneParent input[name='parentResourceName']'").val();

    if (parentName == '') {
        return false;
    }

    if (parentID == '' || parentID == null) {
        $('#span_error_parentResourceName').html(_("Error - Parent is not found.  Please use the Autocomplete."));
        return false;
    }

    var newParentValue = $('.parentResource_new').clone();
    newParentValue.removeClass('parentResource_new');
    newParentValue.attr('disabled', 'disabled');
    var newParentStr = "<div class='oneParent'></div>";
    var newParentObj = $(newParentStr);
    var newParentEnd = "<a href='javascript:void();'><img src='images/cross.gif' alt='"+_("remove parent")+"' title='"+_("remove parent")+"' class='removeParent' /></a></div>";
    newParentObj.append(newParentValue);
    newParentObj.append(newParentEnd);
    $('#existingParent').append(newParentObj);
    $('#newParent input').val('');
});


$(".addOrganization").live('click', function () {

    var typeID = $('.newOrganizationTable').children().children().children().children('.organizationRoleID').val();
    var orgID = $('.newOrganizationTable').children().children().children().children('.organizationID').val();
    var orgName = $('.newOrganizationTable').children().children().children().children('.organizationName').val();

    if ((orgID == '') || (orgID == null) || (typeID == '') || (typeID == null)){
        if ((orgName== '') || (orgName == null) || (typeID == '') || (typeID == null)){
            $('#div_errorOrganization').html(_("Error - Both fields are required"));
        }else{
            $('#div_errorOrganization').html(_("Error - Organization is not found.  Please use the Autocomplete."));
        }

        return false;

    }else{
        $('#div_errorOrganization').html('');

        //first copy the new organization being added
        var originalTR = $('.newOrganizationTR').clone();

        //next append to to the existing table
        //it's too confusing to chain all of the children.
        $('.newOrganizationTR').appendTo('.organizationTable');

        $('.newOrganizationTR').children().children().children('.addOrganization').attr({
            src: 'images/cross.gif',
            alt: _("remove this organization"),
            title: _("remove this organization")
        });
        $('.newOrganizationTR').children().children().children('.addOrganization').addClass('remove');
        $('.organizationRoleID').addClass('changeSelect');
        $('.organizationRoleID').addClass('idleField');
        $('.organizationRoleID').css("background-color","");
        $('.organizationName').addClass('changeInput').removeClass('changeAutocomplete');
        $('.organizationName').addClass('idleField');
        $('.organizationName').css("background-color","");




        $('.addOrganization').removeClass('addOrganization');
        $('.newOrganizationTR').removeClass('newOrganizationTR');



        //next put the original clone back, we just need to reset the values
        originalTR.appendTo('.newOrganizationTable');
        $('.newOrganizationTable').children().children().children().children('.organizationRoleID').val('');
        $('.newOrganizationTable').children().children().children().children('.organizationName').val('');
        $('.newOrganizationTable').children().children().children().children('.organizationID').val('');

        //put autocomplete back
        $('.newOrganizationTable').children().children().children().children('.organizationName').autocomplete('ajax_processing.php?action=getOrganizationList', {
            minChars: 2,
            max: 20,
            mustMatch: false,
            width: 164,
            delay: 10,
            matchContains: true,
            formatItem: function(row) {
                return "<span style='font-size: 80%;'>" + row[0] + "</span>";
            },
            formatResult: function(row) {
                return row[0].replace(/(<.+?>)/gi, '');
            }

        });

        //once something has been selected, change the hidden input value
        $('.newOrganizationTable').children().children().children().children('.organizationName').result(function(event, data, formatted) {
            $(this).parent().children('.organizationID').val(data[1]);
        });



        return false;

    }
});

// @annelhote : Implement function to add new tuto to a resource
$('.addTuto').click(function() {
    var clone = $('.tutoToFill').clone();
    clone.removeClass('tutoToFill');
    clone.addClass('tutoFilled');
    clone.find('.addTutoNameToFill').val($('.addTutoName').val());
    clone.find('.addTutoNameFrToFill').val($('.addTutoFrName').val());
    clone.find('.addTutoUrlToFill').val($('.addTutoUrl').val());
    clone.show();
    $('.addTutoName').val('');
    $('.addTutoFrName').val('');
    $('.addTutoUrl').val('');
    $('.tutoTable tr').last().after(clone);
});

// @annelhote : Implement function to remove tuto to a resource
$('.removeTuto').live('click', function() {
    $(this).parent().parent().fadeTo(400, 0, function () {
        $(this).parent().remove();
    });
    return false;
});

function validateForm (){
    myReturn=0;
    if (!validateRequired('titleText',"<br />"+_("Name must be entered to continue."))) myReturn="1";


    //for verifying org and aliases
    var typeID = $('.newAliasTable').children().children().children().children('.aliasTypeID').val();
    var aName = $('.newAliasTable').children().children().children().children('.aliasName').val();

    var roleID = $('.newOrganizationTable').children().children().children().children('.organizationRoleID').val();
    var orgID = $('.newOrganizationTable').children().children().children().children('.organizationID').val();
    var orgName = $('.newOrganizationTable').children().children().children().children('.organizationName').val();

    //check organizations fields
    if (((orgID == '') || (orgID == null) || (roleID == '') || (roleID == null)) && ((roleID != '') || (orgID != ''))){
        if ((orgName== '') || (orgName == null) || (typeID == '') || (typeID == null)){
            $('#div_errorOrganization').html(_("Error - Both fields are required"));
        }else{
            $('#div_errorOrganization').html(_("Error - Organization is not found.  Please use Autocomplete."));
        }

        myReturn="1";

    }	

    //check aliases
    if (((aName == '') || (aName == null) || (typeID == '') || (typeID == null)) && ((aName != '') || (typeID != ''))){
        $('#div_errorAlias').html(_("Error - Both fields are required"));
        myReturn="1";

    }
    if (myReturn == "1"){
        return false; 	
    }else{
        return true;
    }
}

function submitProductForm(){

    aliasTypeList ='';
    $(".aliasTypeID").each(function(id) {
        aliasTypeList += $(this).val() + ":::";
    }); 

    aliasNameList ='';
    $(".aliasName").each(function(id) {
        aliasNameList += $(this).val() + ":::";
    }); 


    organizationList ='';
    $(".organizationID").each(function(id) {
        organizationList += $(this).val() + ":::";
    }); 

    organizationRoleList ='';
    $(".organizationRoleID").each(function(id) {
        organizationRoleList += $(this).val() + ":::";
    }); 



    if (validateForm() === true) {
        var arrayisbn = Array();
        var isbnOrIssn = '';
        $("input[name='isbnOrISSN']").each(function() {
            arrayisbn.push($(this).val());
        });
        isbnOrIssn = arrayisbn.join();

        var arrayparents = Array();
        var parents = '';
        $("input[name='parentResourceID']").each(function() {
            if ($(this).val() != null && $(this).val() != '') {
                arrayparents.push($(this).val());
            }
        });

        // @annelhote : Add resource's languages
        var resourceLanguages = Array();
        $('input[name="languages"]:checked').each(function() {
            resourceLanguages.push($(this).val());
        });

        // @annelhote : Set resource's accessibility
        var accessibility = 0;
        if($('#accessibility').attr('checked')) {
            accessibility = 1;
        }

        // @annelhote : Set resource's publication status
        var published = 0;
        if($('#published').attr('checked')) {
            published = 1;
        }

        // @annelhote : Format resource's publication date
        d = new Date($('#publicationDate').val());

        // @annelhote : Set tuts array
        var arrayTutos = Array();
        $('.tutoFilled').each(function() {
            arrayTutos.push({
                'name' : $(this).find('.addTutoNameToFill').val(),
                'name_fr' : $(this).find('.addTutoNameFrToFill').val(),
                'url' : $(this).find('.addTutoUrlToFill').val()});
        });

        // @annelhote : Add resource's status
        // @annelhote : Add resource's languages
        // @annelhote : Add resource's logo
        // @annelhote : Add resource's accessibility
        // @annelhote : Add resource's publication status
        // @annelhote : Add resource's publication comment
        // @annelhote : Add resource's tutos
        // @annelhote : Add title translation in french
        // @annelhote : Add description translation in french
        $('#submitProductChanges').attr("disabled", "disabled");
        $.ajax({
            type:       "POST",
            url:        "ajax_processing.php?action=submitProductUpdate",
            cache:      false,
            data:       { resourceID: $("#editResourceID").val(), titleText: $("#titleText").val(), parentResourcesID: JSON.stringify(arrayparents), descriptionText: $("#descriptionText").val(), resourceURL: $("#resourceURL").val(), resourceAltURL: $("#resourceAltURL").val(), resourceFormatID: $("#resourceFormatID").val(), resourceTypeID: $("#resourceTypeID").val(), archiveInd: getCheckboxValue('archiveInd'), aliasTypes: aliasTypeList, aliasNames: aliasNameList, organizationRoles: organizationRoleList, organizations: organizationList, isbnOrISSN: JSON.stringify(arrayisbn), resourceLanguages: JSON.stringify(resourceLanguages), resourceStatusID: $("#resourceStatusID").val(), resourceLogo: $('#resourceLogoFileName').val(), accessibility: accessibility, published: published, publicationComment: $("#publicationComment").val(), publicationDate: d.asString('yyyy-mm-dd'), tutoResource : JSON.stringify(arrayTutos), titleText_fr: $("#titleText_fr").val(), descriptionText_fr: $("#descriptionText_fr").val() },
            success:    function(html) {
                if (html){
                    $("#span_errors").html(html);
                    $("#submitProductChanges").removeAttr("disabled");
                }else{
                    kill();
                    window.parent.tb_remove();
                    window.parent.updateProduct();
                    window.parent.updateRightPanel();
                    window.parent.updateTitle();
                    return false;
                }
            }
        });
    }
}

//kill all binds done by jquery live
function kill() {
    $('.addAlias').die('click'); 
    $('.addOrganization').die('click');
    $('.changeDefault').die('blur');
    $('.changeDefault').die('focus');
    $('.changeInput').die('blur');
    $('.changeInput').die('focus');
    $('.changeAutocomplete').die('blur');
    $('.changeAutocomplete').die('focus');
    $('.select').die('blur');
    $('.select').die('focus');
    $('.organizationName').die('focus');
    $('.remove').die('click');
}