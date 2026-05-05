import {useNeos} from './NeosContext';

export interface IGlobalRegistry {
    get(key: string): {
        get: <T>(key: string) => T
        getAllAsList: <T>() => T[]
        set(key: string, value: any): void
    } | undefined
    set(key: string, value: any): void
}

export type Registry = {
    get<T = unknown>(key: string): T;
    getAllAsList<T = unknown>(): T[];
    set(key: string, value: any): void;
};

export function useGlobalRegistry(): IGlobalRegistry {
    const neos = useNeos();
    return neos.globalRegistry;
}