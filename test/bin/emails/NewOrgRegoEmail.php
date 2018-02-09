<?php 
		$from = "Safe Ministry Training <admin@safeministrytraining.com.au>";
		$subject = "Welcome to Safe Ministry Training Online"; 
		$message = "
<p style='text-align: center;'><img alt='' src='http://online.safeministrytraining.com.au/img/SMTonTrans.png' style='height:165px; margin-bottom:10px; margin-top:10px; width:250px' /></p>

<p>Thanks for registering your church at Safe Ministry Trianing Online. We&#39;re committed to helping churches be safe, legal and insurable. You&#39;re already a step closer now.</p>

<p>Before you start sending your staff and volunteers to do the online training, there&#39;s a few things you should get in place:</p>

<ol>
	<li><strong>Login to the SafeMinistryTraining.com.au Church Dashboard</strong><br />
	This is where you&#39;ll get an overview of all your trainees, and where they&#39;re up to with their verification.<br />
	&nbsp;</li>
	<li><strong>Update your Church Policy Settings</strong> (Dashboard &gt; Policy Settings)<br />
	You need to provide a link to your Church Policy and the contact details for your Safe Ministry Supervisors - the people at your church/organisation who should be ready to hear any reports or concerns about abuse or neglect.&nbsp;<br />
	You might also have additional training notes for your trainees, which you can put in here too.<br />
	(If you&#39;re in NSW include your WWCC login details so we can automatically verify your trainees).<br />
	When your church trainees do the online training, we&#39;ll email them the Policy, the Safe Ministry Supervisors&#39; contact details and your addional training notes.<br />
	&nbsp;</li>
	<li><strong>Buy Training Credits for your trainees (?)</strong><br />
	If&nbsp;you want to pay for your staff/volunteers to do the training upfront, then you&#39;ll need to buy some&nbsp;Training Credits. They are $10 each, or you can get some extra if you buy in bulk.<br />
	If&nbsp;you don&#39;t buy Training Credits, that&#39;s ok, we&#39;ll just get your trainees to pay as they do the training.</li>
</ol>

<p>Once all that is sorted, you can start sending your staff/volunteers email&nbsp;invitations to do the training online.</p>

<p>After each person has completed the training, we&#39;ll email them a certificate, and we&#39;ll email you an update.</p>

<p>If you have any questions about using Safe Ministry Training Online, please let us know,</p>

<p>Cheers,</p>

<p>Dave Moore &amp; the SafeMinistryTraining.com.au Team<br />
info@safeministrytraining.com.au</p>

<p>&nbsp;</p>

";

$headers = 'MIME-Version:1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . "From:" . $from;
    
		
		mail($to,$subject,$message,$headers); 

?>






