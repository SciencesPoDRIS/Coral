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

$(document).ready(function() {


    $(".submitResource").click(function() {
        submitResource($(this).attr("id"));
    });



    //do submit if enter is hit
    $('#titleText').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
        }
    });



    //do submit if enter is hit
    $('#providerText').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
        }
    });



    //do submit if enter is hit
    $('#resourceURL').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
        }
    });

    //do submit if enter is hit
    $('#resourceAltURL').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
        }
    });

    //do submit if enter is hit
    $('#resourceFormatID').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
        }
    });



    //do submit if enter is hit
    $('#resourceTypeID').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
        }
    });


    //do submit if enter is hit
    $('#acquisitionTypeID').keyup(function(e) {
        if (e.keyCode == 13) {
            submitResource('save');
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

    // @annelhote : Add a datePicker on the publication date
    $("#publicationDate").datePicker();

    // @annelhote : if "published" is checked, display the publication comment and the publication date
    if($('#published').attr('checked')) {
        $('.publicationComment').show();
        $('.publicationDate').show();
    } else {
        $('.publicationComment').hide();
        $('.publicationDate').hide();
    }
    $('#published').click(
        function() {
            if($('#published').attr('checked')) {
                $('.publicationComment').show();
                $('.publicationDate').show();
            } else {
                $('.publicationComment').hide();
                $('.publicationDate').hide();
            }
        }
    );

    //check this name/title to make sure it isn't already being used
    $("#titleText").keyup(function() {
        $.ajax({
            type: "GET",
            url: "ajax_processing.php",
            cache: false,
            async: true,
            data: "action=getExistingTitle&name=" + $("#titleText").val(),
            success: function(exists) {
                if (exists == "0") {
                    $("#span_error_titleText").html("");
                } else {
                    $("#span_error_titleText").html("<br />" + _("Warning: this name already exists."));
                }
            }
        });
    });

    $("#providerText").autocomplete('ajax_processing.php?action=getOrganizationList', {
        minChars: 2,
        max: 20,
        mustMatch: false,
        width: 223,
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
    $("#providerText").result(function(event, data, formatted) {
        $('#organizationID').val(data[1]);
    });

    //the following are all to change the look of the inputs when they're clicked
    $('.changeDefaultWhite').live('focus', function(e) {
        if (this.value == this.defaultValue) {
            this.value = '';
        }
    });

    $('.changeDefaultWhite').live('blur', function() {
        if (this.value == '') {
            this.value = this.defaultValue;
        }
    });


    $('.changeInput').addClass("idleField");

    $('.changeInput').live('focus', function() {


        $(this).removeClass("idleField").addClass("focusField");

        if (this.value != this.defaultValue) {
            this.select();
        }

    });


    $('.changeInput').live('blur', function() {
        $(this).removeClass("focusField").addClass("idleField");
    });


    $('.changeAutocomplete').live('focus', function() {
        if (this.value == this.defaultValue) {
            this.value = '';
        }

    });


    $('.changeAutocomplete').live('blur', function() {
        if (this.value == '') {
            this.value = this.defaultValue;
        }
    });




    $('select').addClass("idleField");
    $('select').live('focus', function() {
        $(this).removeClass("idleField").addClass("focusField");

    });

    $('select').live('blur', function() {
        $(this).removeClass("focusField").addClass("idleField");
    });



    $('textarea').addClass("idleField");
    $('textarea').focus(function() {
        $(this).removeClass("idleField").addClass("focusField");
    });

    $('textarea').blur(function() {
        $(this).removeClass("focusField").addClass("idleField");
    });


    $(".remove").live('click', function() {
        $(this).parent().parent().parent().fadeTo(400, 0, function() {
            $(this).remove();
        });
        return false;
    });

    // @annelhote : add autocomplete on the parent input
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

    // @annelhote : add autocomplete on the parent input
    // once something has been selected, change the hidden input value
    $("input[name='parentResourceName']").result(function(event, data, formatted) {
        inputid = $(this).next();
        if (data[1] != $("#editResourceID").val()) {
            inputid.val(data[1]);
            $(this).next().next().html('');
        } else {
            $(this).next().next().html("<br />" + _("Error - Parent cannot be the same as the child"));
        }
    });

    // @annelhote : Remove parent
    $(".removeParent").live('click', function() {
        $(this).parent().fadeTo(400, 0, function() {
            $(this).parent().remove();
        });
        return false;
    });

    // @annelhote : Add parent on click on the "add" button
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
        var newParentEnd = "<a href='javascript:void();'><img src='images/cross.gif' alt='" + _("remove parent") + "' title='" + _("remove parent") + "' class='removeParent' /></a></div>";
        newParentObj.append(newParentValue);
        newParentObj.append(newParentEnd);
        $('#existingParent').append(newParentObj);
        $('#newParent input').val('');
    });

    //  @annelhote : Add ISSN on click on the "add" button
    $(".addIsbn").live('click', function() {
        var newIsbn = $('.isbnOrISSN_new').clone();
        newIsbn.removeClass('isbnOrISSN_new');
        newIsbn.appendTo('#existingIsbn');
        $("#existingIsbn").append('<br />');
        $('#newIsbn input').val('');
    });

    // @annelhote : add autocompletion on organisation
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

    // @annelhote
    // once something has been selected, change the hidden input value
    $(".organizationName").result(function(event, data, formatted) {
        $(this).parent().children('.organizationID').val(data[1]);
    });

    // @annelhote : Add organization on click on "add" button
    $(".addOrganization").live('click', function() {
        var typeID = $('.newOrganizationTable').children().children().children().children('.organizationRoleID').val();
        var orgID = $('.newOrganizationTable').children().children().children().children('.organizationID').val();
        var orgName = $('.newOrganizationTable').children().children().children().children('.organizationName').val();
        if ((orgID == '') || (orgID == null) || (typeID == '') || (typeID == null)) {
            if ((orgName == '') || (orgName == null) || (typeID == '') || (typeID == null)) {
                $('#div_errorOrganization').html(_("Error - Both fields are required"));
            } else {
                $('#div_errorOrganization').html(_("Error - Organization is not found.  Please use the Autocomplete."));
            }
            return false;
        } else {
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
            $('.organizationRoleID').css("background-color", "");
            $('.organizationName').addClass('changeInput').removeClass('changeAutocomplete');
            $('.organizationName').addClass('idleField');
            $('.organizationName').css("background-color", "");
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

    // @annelhote : Implement function to remove tuto from a resource
    $('.removeTuto').live('click', function() {
        $(this).parent().parent().fadeTo(400, 0, function() {
            $(this).parent().remove();
        });
        return false;
    });

    // @annelhote : Implement function to add new alias to a resource
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

});


function validateNewResource() {
    myReturn = 0;

    var title = $('#titleText').val();
    var fmtID = $('#resourceFormatID').val();
    var typeID = $('#resourceTypeID').val();

    //also perform same checks on the current record in case add button wasn't clicked
    if (title == '' || title == null) {
        $('#span_error_titleText').html(_("A title must be entered to continue."));
        myReturn = 1;
    }

    // @annelhote : the resource format is not required anymore
    // if (fmtID == '' || fmtID == null) {
    //     $('#span_error_resourceFormatID').html(_("The resource format is required."));
    //     myReturn = 1;
    // }

    // @annelhote : set type as multivaluated
    // if (typeID == '' || typeID == null) {
    if($('input[name="types"]:checked').length == 0) {
        $('#span_error_resourceTypeID').html(_("The resource type is required."));
        myReturn = 1;
    }

    if (myReturn == 1) {
        return false;
    } else {
        return true;
    }
}





function submitResource(status) {

    orderTypeList = '';
    $(".orderTypeID").each(function(id) {
        orderTypeList += $(this).val() + ":::";
    });

    fundNameList = '';
    $(".fundName").each(function(id) {
        fundNameList += $(this).val() + ":::";
    });

    paymentAmountList = '';
    $(".paymentAmount").each(function(id) {
        paymentAmountList += $(this).val() + ":::";
    });

    currencyCodeList = '';
    $(".currencyCode").each(function(id) {
        currencyCodeList += $(this).val() + ":::";
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

    // @annelhote : Set tutos array
    var arrayTutos = Array();
    $('.tutoFilled').each(function() {
        arrayTutos.push({
            'name' : $(this).find('.addTutoNameToFill').val(),
            'url' : $(this).find('.addTutoUrlToFill').val()});
    });

    // @annelhote : Format resource's publication date
    d = new Date($('#publicationDate').val());

    //  @annelhote : Set isbnOrIssn value to be saved
    var arrayisbn = Array();
    var isbnOrIssn = '';
    $("input[name='isbnOrISSN']").each(function() {
        arrayisbn.push($(this).val());
    });
    isbnOrIssn = arrayisbn.join();

    //  @annelhote : Set parents array to be saved
    var arrayparents = Array();
    var parents = '';
    $("input[name='parentResourceID']").each(function() {
        if ($(this).val() != null && $(this).val() != '') {
            arrayparents.push($(this).val());
        }
    });

    // @annelhote : Set organizations list to be linked to the resource
    organizationList ='';
    $(".organizationID").each(function(id) {
        organizationList += $(this).val() + ":::";
    });

    // @annelhote : Set organizations role to be linked to the resource
    organizationRoleList ='';
    $(".organizationRoleID").each(function(id) {
        organizationRoleList += $(this).val() + ":::";
    });

    // @annelhote : Set alias type list to be linked to the resource
    aliasTypeList ='';
    $(".aliasTypeID").each(function(id) {
        aliasTypeList += $(this).val() + ":::";
    });

    // @annelhote : Set alias name list to be linked to the resource
    aliasNameList ='';
    $(".aliasName").each(function(id) {
        aliasNameList += $(this).val() + ":::";
    });

    // @annelhote : Add resource's types
    var resourceTypes = Array();
    $('input[name="types"]:checked').each(function() {
        resourceTypes.push($(this).val());
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
    if (validateNewResource() === true) {
        $('.submitResource').attr("disabled", "disabled");

        $.ajax({
            type: "POST",
            url: "ajax_processing.php?action=submitNewResource",
            cache: false,
            // data: { resourceID: $("#editResourceID").val(), resourceTypeID: $("input:radio[name='resourceTypeID']:checked").val(), resourceFormatID: $("input:radio[name='resourceFormatID']:checked").val(), acquisitionTypeID: $("input:radio[name='acquisitionTypeID']:checked").val(), titleText: $("#titleText").val(), descriptionText: $("#descriptionText").val(), providerText: $("#providerText").val(), organizationID: $("#organizationID").val(), resourceURL: $("#resourceURL").val(), resourceAltURL: $("#resourceAltURL").val(), noteText: $("#noteText").val(), orderTypes: orderTypeList, fundNames: fundNameList, paymentAmounts: paymentAmountList, currencyCodes: currencyCodeList, resourceStatus: status, resourceStatusID: $("#resourceStatusID").val(), resourceLogo: $('#resourceLogoFileName').val(), accessibility: accessibility, published: published, publicationComment: $("#publicationComment").val(), publicationDate: d.asString('yyyy-mm-dd'), tutoResource : JSON.stringify(arrayTutos), titleText_fr: $("#titleText_fr").val(), descriptionText_fr: $("#descriptionText_fr").val(), resourceLanguages: JSON.stringify(resourceLanguages) },
            data: { resourceID: $("#editResourceID").val(), resourceFormatID: $("input:radio[name='resourceFormatID']:checked").val(), acquisitionTypeID: $("input:radio[name='acquisitionTypeID']:checked").val(), titleText: $("#titleText").val(), descriptionText: $("#descriptionText").val(), providerText: $("#providerText").val(), organizationID: $("#organizationID").val(), resourceURL: $("#resourceURL").val(), resourceAltURL: $("#resourceAltURL").val(), noteText: $("#noteText").val(), orderTypes: orderTypeList, fundNames: fundNameList, paymentAmounts: paymentAmountList, currencyCodes: currencyCodeList, resourceStatus: status, resourceStatusID: $("#resourceStatusID").val(), resourceLogo: $('#resourceLogoFileName').val(), accessibility: accessibility, published: published, publicationComment: $("#publicationComment").val(), publicationDate: d.asString('yyyy-mm-dd'), tutoResource : JSON.stringify(arrayTutos), titleText_fr: $("#titleText_fr").val(), descriptionText_fr: $("#descriptionText_fr").val(), resourceLanguages: JSON.stringify(resourceLanguages), archiveInd: getCheckboxValue('archiveInd'), isbnOrISSN: JSON.stringify(arrayisbn), parentResourcesID: JSON.stringify(arrayparents), organizationRoles: organizationRoleList, organizations: organizationList, aliasTypes: aliasTypeList, aliasNames: aliasNameList, resourceTypes: JSON.stringify(resourceTypes) },
            success: function(resourceID) {
                //go to the new resource page if this was submitted
                if (status == 'progress') {
                    window.parent.location = ("resource.php?ref=new&resourceID=" + resourceID);
                    tb_remove();
                    return false;
                    //otherwise go to queue
                } else {
                    window.parent.location = ("queue.php?ref=new");
                    tb_remove();
                    return false;

                }

            }


        });

    }

}





//kill all binds done by jquery live
function kill() {
    $('.remove').die('click');
    $('.changeAutocomplete').die('blur');
    $('.changeAutocomplete').die('focus');
    $('.changeDefault').die('blur');
    $('.changeDefault').die('focus');
    $('.changeInput').die('blur');
    $('.changeInput').die('focus');
    $('.select').die('blur');
    $('.select').die('focus');
    $('.changeDefaultWhite').die('focus');
    $('.changeDefaultWhite').die('blur');
    $('.removeParent').die('click');
    $('.addParent').die('click');
    $('.addIsbn').die('click');
    $('.addOrganization').die('click');
    $('.removeTuto').die('click');
    $('.addAlias').die('click');
}