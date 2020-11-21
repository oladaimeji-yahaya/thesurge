@extends('layouts.directory')
@section('content')
@php
$name = strtoupper(config('app.name'));
@endphp


<section class="banner-area" style="background-image: url('../images/backgrounds/policy.jpg');">
    <div class="banner-overlay">
        <div class="banner-text text-center">
            <div class="container">
                <!-- Section Title Starts -->
                <div class="row text-center">
                    <div class="col-xs-12">
                        <!-- Title Starts -->
                        <h2 class="title-head">Privacy <span>Policy</span></h2>
                        <!-- Title Ends -->
                        <hr>
                        <!-- Breadcrumb Starts -->
                        <ul class="breadcrumb">
                            <li><a href="index.html"> home</a></li>
                            <li>privacy</li>
                        </ul>
                        <!-- Breadcrumb Ends -->
                    </div>
                </div>
                <!-- Section Title Ends -->
            </div>
        </div>
    </div>
</section>
<!-- Banner Area Ends -->


<section id="privacy">
    <div class="container">
        <div class="padding-1em white">
            <h1 align="left" style="color:#000;"><strong>Privacy Policy</strong></h1>
            <hr />
            <div style="text-align: left">
                <div>
                    <p>
                        We are committed to preserving the privacy of all visitors and users of this website.
                    </p>
                    <h3>Policy Changes</h3>
                    <p>
                        We conduct periodic reviews of “Our Privacy Policy” to adhere to any new laws or technological changes to our operations and practices and to ensure it remains appropriate to the changing environment. Any updates or changes to the policy will be posted on this website with the new revision date which is the effective date of changes. Your continued use of this website constitutes your acceptance of any changes to this policy.
                    </p>
                    <h3>Disclosure</h3>
                    <p>
                        Personal information given to us by you shall not be disclosed to any third party.
                    </p>
                    <h3>Amendments to Policy</h3>
                    <p>
                        If you have any questions that this statement does not address, please contact us by email or through one of our management
                    </p>
                    <h3>Terms and Conditions of Use</h3>
                    <ul>
                        By participation in this program, you must acknowledge and agree to the following terms:
                        <li>
                            The clients have to follow the rules and regulations being made by the company not only for their convenience but also for the company’s benefits.
                        </li>
                        <li>
                            English is the official language of the website and company, all the issues will be solved according to the set language.
                        </li>
                        <li>
                            You agree to be of legal age in your country, and in all the cases your minimal age is 18 years of old.
                        </li>
                        <li>
                            Every transaction made between our clients and its partners is considered to be private.
                        </li>
                        <li>
                            All members information, financial reports, account balances, messages and other info displayed and/or stored by {{$name}} are of private nature, and will not be disclosed to third parties.
                        </li>
                        <li>
                            You agree to hold {{$name}} harmless from any loss and/or liability to your purchase, therefore do not purchase services that you can not afford to pay for, as you are spending it at your own risk.
                        </li>
                        <li>
                            {{$name}} is not responsible and/or liable for any internal or external loss of funds due to password sharing and/or Identity theft.
                        </li>
                        <li>
                            You agree that all discussed information and/or replies coming from {{$name}} by any means of communication are of private nature, therefore must be kept confidential and protected by copyright from any disclosure.
                        </li>
                        <li>
                            Only promote {{$name}} using legal methods. Any income promises or guarantees inconsistent with the information provided by {{$name}} may result in a permanent account suspension.
                        </li>
                        <li>
                            {{$name}} will not be held responsible for any harm and/or loss made to any person or group by our members and/or visitors. Therefore, both members and visitors of {{$name}} take full responsibility for their methods of promoting and marketing {{$name}}, and it must fully comply with this written terms.
                        </li>
                        <li>
                            The information, communications and/or any materials {{$name}} contains are for educational and informational purposes, and is not to be regarded as a solicitation for making purchases in any jurisdiction which deems a non-public offers or solicitations unlawful, nor to any person whom it will be unlawful to make such an offer and/or solicitation.
                        </li>
                        <li>
                            You acknowledge that you are acting as an individual and not on behalf of any other entity and/or any authority. Our offer is void where prohibited by law.
                        </li>
                        <li>
                            {{$name}} reserve the right to suspend and/or close the operation of this site or sections thereof when, as a result of political, economic, military, monetary events and/or any other circumstances outside the control, responsibility, and power of {{$name}} site and company.
                        </li>

                        <li>
                            You agree to not use our company name and/or {{$name}} site name in any connection/relation to sending spam, unsolicited emails, and/or any other way there is.
                        </li>
                        <li>
                            If you have violated this "Zero-Tolerance" Anti Spam Policy, you will lose the rights of using our service and all of your account privileges will be immediately revoked.
                        </li>
                        <li>
                            Violation of any kind to the terms and/or conditions mentioned here before will get you permanently removed from this program, you will lose your rights to use {{$name}} services, and all of your account privileges will be immediately revoked.
                        </li>

                        <li>
                            Any inquiries should be addressed in writing to our dedicated support via the contact page.
                        </li>
                    </ul>

                    <h3>Risk Disclosure</h3>
                    <p>
                        The execution of financial transactions, similar in nature to the transactions contemplated and described in this agreement involves the use of a financial leverage. The use of a high financial leverage coupled with the execution of the transactions described in this agreement should be considered as high-risk financial activities. You should carefully consider whether this kind of financial activity suits your needs, your financial resources, and your personal circumstances.
                    </p>
                    <p>
                        The cautions detailed in this disclosure section do not include all possible risks associated with the kind of transactions contemplated under this agreement.
                    </p>
                    <p>
                        By registering to the Site opening an account and carrying out Transactions, you hereby approve that you are aware of the following:
                    </p>
                    <p>
                        The type of Transactions offered by the System may be considered special risk transactions and carrying them out might involve risk.
                    </p>
                    <p>
                        In order to complete any withdrawal, all customers must complete {{$name}} withdrawal compliance procedures.
                    </p>
                    <p>
                        You read the terms of this Agreement and all terms relating to Financial Contracts as they are defined in this Agreement prior to the execution of any Financial Contract and fully understand the consequences and results of success or failure.
                    </p>
                    <p>
                        You know that incorrect investment may cause you loss.
                    </p>
                    <p>
                        The use of the System is solely designated for sophisticated users with the ability to sustain swift losses up to total loss of the invested money and/or the securities. You are responsible for careful consideration whether such Transactions suits you and your purposes while taking into consideration your resources, your personal circumstances and understanding the implications of actions made by yourself. It is highly recommended that you consult with tax experts and legal advisors.
                    </p>
                    <p>
                        {{$name}} has the right to refuse service to any partner in the event that the partner violates these Terms or attempts to harm {{$name}}, including attempts to slander, discredit, or blackmail {{$name}}.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('head')
<style>
    #privacy ul{
        list-style: disc;
    }
    #privacy ul li{
        margin-left: 15px;
    }
</style>
@endsection