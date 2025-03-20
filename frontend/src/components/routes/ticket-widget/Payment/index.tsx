import {useParams} from "react-router-dom";
// import {loadStripe, Stripe} from "@stripe/stripe-js";
// import {Elements} from "@stripe/react-stripe-js";
// import StripeCheckoutForm from "../../../forms/StripeCheckoutForm";
import {useCreateStripePaymentIntent} from "../../../../queries/useCreateStripePaymentIntent.ts";
import {useEffect, useState} from "react";
import {LoadingMask} from "../../../common/LoadingMask";
import {CheckoutContent} from "../../../layouts/Checkout/CheckoutContent";
import {t} from "@lingui/macro";
import {eventHomepagePath} from "../../../../utilites/urlHelper.ts";
import {useGetEventPublic} from "../../../../queries/useGetEventPublic.ts";
import {HomepageInfoMessage} from "../../../common/HomepageInfoMessage";
// import {getConfig} from "../../../../utilites/config.ts";

const Payment = () => {
    const {eventId, orderShortId} = useParams();
    const {
        data: data,
        isFetched: isStripeFetched,
        error: stripePaymentIntentError
    } = useCreateStripePaymentIntent(eventId, orderShortId);
    const {data: event} = useGetEventPublic(eventId);

    useEffect(() => {
        if (isStripeFetched && data?.redirect_url) {
            window.location.href = data.redirect_url;
        }
    }, [data, isStripeFetched]);

    if (stripePaymentIntentError && event) {
        return (
            <CheckoutContent>
                <HomepageInfoMessage
                    /* @ts-ignore */
                    message={stripePaymentIntentError.response?.data?.message || t`Sorry, something has gone wrong. Please restart the checkout process.`}
                    link={eventHomepagePath(event)}
                    linkText={t`Return to event page`}
                />
            </CheckoutContent>
        );
    }

    if (!isStripeFetched) {
        return (
            <CheckoutContent>
                <HomepageInfoMessage
                    message={t`Redirecting to payment portal...`}
                />
                <LoadingMask/>
            </CheckoutContent>
        );
    }

    return <LoadingMask/>;
}

export default Payment;