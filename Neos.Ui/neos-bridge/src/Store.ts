import * as React from 'react';

import {useNeos} from './NeosContext';

export interface IStore {
    getState(): any;
    subscribe(listener: () => void): () => void;
    dispatch(action: any): any;
}

export function useStore(): IStore {
    return useNeos().store;
}

export function useNeosSelector<R>(selector: (state: any) => R): R {
    const store = useStore();
    const [result, setResult] = React.useState<R>(() =>
        selector(store.getState())
    );

    React.useEffect(
        () =>
            store.subscribe(() => {
                setResult(selector(store.getState()));
            }),
        [store, selector]
    );

    return result;
}
