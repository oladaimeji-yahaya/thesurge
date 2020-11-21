@extends('layouts.directory')
@section('head')
    @parent
    <style>
        div > ol > li{
            margin-top: 1em;
        }
    </style>
@endsection

@section('content')
    @php
        $name = strtoupper(config('app.name'));
        $domain = config('app.url');
    @endphp



    <!-- Section Terms of Services Starts -->
    <section class="terms-of-services padding-top-4em">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 margin-top-4em">
                    <h3>Terms and Conditions</h3>
                    <p>
                        These Terms and Conditions exist for the benefit of {{$name}} and its members.
                    </p>
                    <ol>
                        <li>
                            <h4>1. Relationship:</h4>
                            <ol>
                                <li>
                                    1.01. By joining our site as a member, you automatically agree to the Terms of
                                    Service, Privacy and Refund Policy of {{$name}}. Members that do not agree to any
                                    of these Terms of Service, Privacy, and Refund Policy, should stop using our
                                    site/service and terminate their account immediately.
                                </li>
                                <li>
                                    1.02. A member is neither an employee nor an independent contractor of {{$name}}.
                                </li>
                                <li>
                                    1.03. Members are totally responsible for the money they spend in our
                                    site {{$name}} in exchange for our services. By investing in our platform, members
                                    can participate in our Cash Back program.
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h4>2. Illegal activity:</h4>
                            <ol>
                                <li>
                                    2.01. Members may not present {{$name}} promotions on any page, newsgroup, email
                                    or any distribution method that is regarded objectionable by {{$name}}, its
                                    Internet Service Providers, or otherwise considered unlawful according to any
                                    controlling legal authority. This includes, but is not limited to, pornography,
                                    computer viruses, obscenity or Spam.
                                </li>
                                <li>
                                    2.02. Members of our site are not allowed to gain referrals by enticing them with
                                    gifts/rewards/any other incentives without explaining the nature of the Trading
                                    Experts business as presented in our site.
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h4>3. Third-Party Liability:</h4>
                            {{$name}} will in no way be liable for the actions of third parties that may in any way
                            cause harm to members.
                        </li>
                        <li>
                            <h4>4. Cash Back Program:</h4>
                            {{$name}} offers a cash back program to the users who invest in any plan from the site.
                            The Trade made from those plans are used to build a diverse portfolio and the profits from
                            this portfolio are backing the cash back program. 70% of the profits are distributed among
                            the participants of the cash back program, 30% is added back to the portfolio.
                        </li>
                        <li>
                            <h4>5. Cancellation/Termination of membership:</h4>
                            <ol>
                                <li>
                                    5.01. Opening an account in {{$name}} is voluntary and therefore the account may
                                    be canceled at any time by the member.
                                </li>
                                <li>
                                    5.02. {{$name}}, at its sole discretion, reserves the right to terminate without
                                    notice, the account of any member caught in violation of the Terms of Use, Privacy
                                    or Refund Policy.
                                </li>
                                <li>
                                    5.03. Any member acting unethical or unprofessional (with acts that can be
                                    documented and proven) may be removed without refund at the sole discretion
                                    of {{$name}} with all current and future commissions and site usage forfeited.
                                </li>
                                <li>
                                    5.04. Any membership that is terminated will forfeit all benefits and privileges
                                    associated with {{$name}}. Any positions held in {{$name}} will also be
                                    forfeited and ownership will be reassigned to {{$name}}.
                                </li>
                                <li>
                                    5.05. We reserve the right to terminate the member's account in cases like, but not
                                    limited to, spamming, cheating, misuse or abuse of our site/program/site
                                    admin(s)/site member(s).
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h4>6. Payments:</h4>
                            All payments are processed automatically. Sometimes, they may take a longer time to get
                            processed because of server issues, etc. and hence please allow up to 1 hour for the payment
                            to be processed. If it takes longer than that, please kindly contact the Support for further
                            assistance.
                        </li>
                        <li>
                            <h4>7. Refund Policy:</h4>
                            7.01. If you make a dispute, Charge back or reverse transactions on your investment, your
                            account will be suspended immediately. On closure of dispute, we will re-evaluate the
                            situation and may either reinstate your account or terminate your account.
                        </li>
                        <li>
                            <h4>8. Privacy Policy:</h4>
                            <ol>
                                <li>
                                    8.01. {{$name}} will never provide personal information about its members to third
                                    parties without the consent of the member unless required by law.
                                </li>
                                <li>
                                    8.02. Your collected information is used for the communication between {{$name}}
                                    and its members and will not be sold to any other company.
                                </li>
                                <li>
                                    8.03. We use cookies to keep track of advertisements and Affiliates. Cookies are
                                    small files that a site or its service provider transfers to your computer’s hard
                                    drive through your web browser (if you allow it) that enables the sites or service
                                    providers to recognize your browser and capture and remember certain information.
                                </li>
                            </ol>
                        </li>
                        <li>
                            <h4>9. Contact Information:</h4>
                            It is the responsibility of members to keep their personal records up to date at the
                            member’s area. {{$name}} will not be responsible for communication errors due to incorrect
                            or out of date contact information. Continued failed attempts to make contact with you may
                            result in the termination of your account.
                        </li>
                        <li>
                            <h4>10. Copyright Material:</h4>
                            All branding, logos and graphics contained within {{$name}} are copyright use;
                            distribution or copying of such content is expressly prohibited.
                        </li>
                        <li>
                            <h4>11. Damaging Intent:</h4>
                            Any Member who engages in chat, email, postings or any other medium, content that is deemed
                            damaging to {{$name}} and/or its members may result in the termination of your account
                            with {{$name}}. Depending on the seriousness, {{$name}} may deem it appropriate to
                            exercise legal action.
                        </li>
                        <li>
                            <h4>12. UCE/UBE or SPAM:</h4>
                            {{$name}} strictly prohibits the use of UCE or SPAM. This enforcement is at the sole
                            discretion of MyPassiveTrades.com and for the benefit of all its members. Members proven to
                            be participating in such activities will result in the termination of your membership.
                        </li>
                        <li>
                            <h4>13. Taxes & Laws:</h4>
                            <ol>
                                <li>
                                    13.01. You, the member, are responsible for any and all taxes payable in you
                                    residency country / domicile or jurisdiction, for any income you receive
                                    from {{$name}}.
                                </li>
                                <li>
                                    13.02. You, the member, are responsible for any laws in your residency
                                    country/domicile or jurisdiction.
                                </li>
                            </ol>
                        </li>
                        <li>
                            14. Disputes and Agreements shall be interpreted under the laws.
                        </li>
                        <li>
                            15. {{$name}} reserves the right to update, modify, add or change the terms and conditions
                            without notice.
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- Section Terms of Services Ends -->

@endsection