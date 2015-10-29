/* @source: http://stackoverflow.com/questions/7713182/copy-to-clipboard-for-all-browsers-using-javascript
 * 
 */
function copyToClipboard(s)
{
    if( window.clipboardData && clipboardData.setData )
    {
        clipboardData.setData("Text", s);
    }
    else
    {
        // You have to sign the code to enable this or allow the action in about:config by changing
        user_pref("signed.applets.codebase_principal_support", true);
        netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');

        var clip = Components.classes['@mozilla.org/widget/clipboard;[[[[1]]]]'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip) return;

        // create a transferable
        var trans = Components.classes['@mozilla.org/widget/transferable;[[[[1]]]]'].createInstance(Components.interfaces.nsITransferable);
        if (!trans) return;

        // specify the data we wish to handle. Plaintext in this case.
        trans.addDataFlavor('text/unicode');

        // To get the data from the transferable we need two new objects
        var str = new Object();
        var len = new Object();

        var str = Components.classes["@mozilla.org/supports-string;[[[[1]]]]"].createInstance(Components.interfaces.nsISupportsString);

        var copytext=meintext;

        str.data=copytext;

        trans.setTransferData("text/unicode",str,copytext.length*[[[[2]]]]);

        var clipid=Components.interfaces.nsIClipboard;

        if (!clip) return false;

        clip.setData(trans,null,clipid.kGlobalClipboard);      
    }
}
/* SAMPLE 
 <textarea id='testText' rows="10" cols="100">Enter your Sample text</textarea><br />
<button onclick="copyToClipboard(document.getElementById('testText').value);" >clipboard</button><br /><br />
<textarea rows="10" cols="100">Paste your text here</textarea><br />
 */