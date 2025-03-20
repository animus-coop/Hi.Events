import {useQuery} from "@tanstack/react-query";
import {orderClientPublic} from "../api/order.client.ts";
import {IdParam} from "../types.ts";
import {getSessionIdentifier} from "../utilites/sessionIdentifier.ts";

export const GET_INITIATE_STRIPE_SESSION_PUBLIC_QUERY_KEY = 'getStripSessionPublic';

export const useCreateStripePaymentIntent = (eventId: IdParam, orderShortId: IdParam) => {
    return useQuery<{ redirect_url: string }, Error>(
        [GET_INITIATE_STRIPE_SESSION_PUBLIC_QUERY_KEY],
        async () => {
            const {redirect_url} = await orderClientPublic.createStripePaymentIntent(
                Number(eventId),
                String(orderShortId),
                getSessionIdentifier(),
            );
            return {redirect_url};
        },
        {
            retry: false,
            staleTime: 0,
            cacheTime: 0,
        }
    );
}